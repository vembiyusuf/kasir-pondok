@extends('layouts.app')

@section('content')
    <div class="px-6 py-6 w-full">
        <div class="bg-white rounded-xl shadow">
            <!-- Header -->
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <div>
                    <h4 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-receipt mr-2 text-blue-500"></i> Detail Transaksi
                    </h4>
                    <div class="flex items-center mt-1 space-x-4">
                        <span class="text-sm text-gray-600">Invoice: <span
                                class="font-medium">{{ $transaction->invoice_code }}</span></span>
                        <span class="text-sm text-gray-600">Tanggal: <span
                                class="font-medium">{{ $transaction->created_at->format('d M Y H:i') }}</span></span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('transactions.receipt', $transaction) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700">
                        <i class="fas fa-print mr-1"></i> Cetak Struk
                    </a>
                    <a href="{{ route('transactions.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded hover:bg-gray-100">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Informasi Transaksi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-6 py-6">
                <!-- Info Pelanggan/Kasir -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Kasir</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Nama Kasir:</span>
                            <p class="font-medium">{{ $transaction->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Waktu Transaksi:</span>
                            <p class="font-medium">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="font-semibold text-gray-700 mb-3 border-b pb-2">Ringkasan Pembayaran</h5>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Belanja:</span>
                            <span class="font-medium">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Tunai:</span>
                            <span class="font-medium">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Kembalian:</span>
                            <span class="font-medium text-green-600">Rp
                                {{ number_format($transaction->change, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Status Transaksi -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="font-semibold text-gray-700 mb-3 border-b pb-2">Status Transaksi</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Status:</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Selesai</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Metode Pembayaran:</span>
                            <p class="font-medium">Tunai</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="px-6 pb-6">
                <h5 class="font-semibold text-gray-700 mb-3">Daftar Produk</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">Produk</th>
                                <th class="px-4 py-2 text-right">Harga Satuan</th>
                                <th class="px-4 py-2 text-right">Jumlah</th>
                                <th class="px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($transaction->details as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2">
                                        <div class="font-medium">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->product->category->name }}</div>
                                    </td>
                                    <td class="px-4 py-2 text-right">Rp
                                        {{ number_format($item->price_at_time, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 text-right font-medium">Rp
                                        {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-medium">
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-right">Total</td>
                                <td class="px-4 py-2 text-right text-green-600">Rp
                                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Catatan -->
            <div class="px-6 pb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h5 class="font-semibold text-blue-700 mb-2">Catatan Transaksi</h5>
                    <p class="text-sm text-gray-600">Transaksi ini dilakukan pada
                        {{ $transaction->created_at->format('d F Y H:i') }} oleh
                        {{ $transaction->user->name ?? 'sistem' }}.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
