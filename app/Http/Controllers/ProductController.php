<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'servings' => 'nullable|array',
            'servings.*.name' => 'required_with:servings|string|max:255',
            'servings.*.price' => 'required_with:servings|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        if (isset($validated['servings'])) {
            $servings = [];
            foreach ($validated['servings'] as $serving) {
                $servings[] = [
                    'name' => $serving['name'],
                    'price' => $serving['price']
                ];
            }
            $validated['servings'] = json_encode($servings); // Encode ke JSON
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'servings' => 'nullable|array',
            'servings.*.name' => 'required_with:servings|string|max:255',
            'servings.*.price' => 'required_with:servings|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        if (isset($validated['servings'])) {
            $servings = [];
            foreach ($validated['servings'] as $serving) {
                $servings[] = [
                    'name' => $serving['name'],
                    'price' => $serving['price']
                ];
            }
            $validated['servings'] = json_encode($servings); // Encode ke JSON
        } else {
            $validated['servings'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
