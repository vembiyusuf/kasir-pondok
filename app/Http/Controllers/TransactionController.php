<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.product']);

        // Apply date filters if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Order by latest first
        $query->orderBy('created_at', 'desc');

        // Paginate results
        $transactions = $query->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'details.product.category']);
        return view('transactions.show', compact('transaction'));
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['user', 'details.product.category']);
        // Render struk di view baru
        return view('transactions.print', compact('transaction'));
    }


    public function export(Request $request)
    {
        try {
            $transactions = Transaction::with(['details.product', 'user'])
                ->when($request->start_date, function ($query) use ($request) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function ($query) use ($request) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return Excel::download(new TransactionsExport($transactions), 'transactions_' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Failed to export: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('stock', '>', 0);
        }])->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.serving_name' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $index = str_replace(['items.', '.serving_name'], '', $attribute);
                    $product = Product::find($request->items[$index]['product_id']);
                    if ($value && $product->servings) {
                        $servings = json_decode($product->servings, true);
                        if (!collect($servings)->contains('name', $value)) {
                            $fail('Jenis penyajian tidak valid untuk produk ini.');
                        }
                    }
                }
            ],
            'payment_method' => 'required|in:cash,card,transfer',
            'amount_paid' => 'required|integer|min:0',
            'discount_amount' => 'nullable|integer|min:0',
            'customer_name' => 'nullable|string|max:255', // Add this line
        ]);

        try {
            DB::beginTransaction();

            // Calculate total (using integer for money)
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::with('category')->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }

                // Determine price
                $price = $product->price;
                $servingName = $item['serving_name'] ?? null;

                if ($servingName && $product->servings) {
                    $servings = json_decode($product->servings, true);
                    $selectedServing = collect($servings)->firstWhere('name', $servingName);
                    $price = $selectedServing ? $selectedServing['price'] : $price;
                }

                $itemSubtotal = $price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'serving_name' => $servingName,
                    'price' => $price,
                    'subtotal' => $itemSubtotal
                ];
            }

            // Calculate totals (using integer)
            $discountAmount = (int)($request->discount_amount ?? 0);
            $total = $subtotal - $discountAmount;

            if ($total < 0) {
                throw new \Exception("Diskon tidak boleh lebih besar dari subtotal");
            }

            $amountPaid = (int)$request->amount_paid;
            if ($amountPaid < $total) {
                throw new \Exception("Jumlah pembayaran tidak mencukupi");
            }

            // Create transaction (all fields included)
            $transaction = Transaction::create([
                'invoice_code' => 'INV-' . date('Ymd') . '-' . str_pad(Transaction::count() + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $total,
                'amount_paid' => $amountPaid,
                'change' => $amountPaid - $total,
                'customer_name' => $request->customer_name, // Add this line
            ]);

            // Create transaction details
            foreach ($itemsData as $itemData) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $itemData['product']->id,
                    'quantity' => $itemData['quantity'],
                    'price_at_time' => $itemData['price'],
                    'serving_type' => $itemData['serving_name'],
                ]);

                $itemData['product']->decrement('stock', $itemData['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'transaction_id' => $transaction->id,
                'subtotal' => $subtotal,
                'discount' => $discountAmount,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'change' => $amountPaid - $total,
                'customer_name' => $request->customer_name, // Add this line
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getProduct($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Get servings if available
        $servings = [];
        if ($product->servings) {
            $servings = json_decode($product->servings, true);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'category' => $product->category->name,
            'servings' => $servings
        ]);
    }

    public function print(Transaction $transaction)
    {
        $transaction->load('details.product');

        return view('transactions.print', [
            'transaction' => $transaction
        ]);
    }
}
