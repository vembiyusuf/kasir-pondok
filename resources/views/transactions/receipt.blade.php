<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                width: 80mm;
                /* Cocok untuk printer thermal kecil */
            }
        }
    </style>
</head>

<body class="max-w-xs mx-auto text-xs font-sans">
    <h2 class="text-center text-base font-bold">PPBA Market</h2>
    <h4 class="text-center text-sm font-semibold mb-2">Struk Pembayaran</h4>

    <div class="mb-2">
        <p>Invoice: <span class="font-semibold">{{ $transaction->invoice_code }}</span></p>
        <p>Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}</p>
        <p>Kasir: {{ $transaction->user->name ?? '-' }}</p>
    </div>

    <div class="mb-2 border-t border-b py-2">
        <table class="w-full">
            <thead class="border-b">
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Sub</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $item)
                    <tr class="border-b last:border-b-0">
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp
                            {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-2 border-t pt-2">
        <div class="flex justify-between">
            <span>Total Belanja</span>
            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tunai</span>
            <span>Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Kembalian</span>
            <span>Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
        </div>
    </div>

    <p class="text-center mt-4">Terima Kasih telah berbelanja!</p>
</body>

</html>
