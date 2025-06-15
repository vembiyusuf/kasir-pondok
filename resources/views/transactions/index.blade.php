@extends('layouts.app')

@section('content')

    <div class="px-6 py-6 w-full">
        <div class="bg-white rounded-xl shadow">
            <!-- Header -->
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h4 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-receipt mr-2 text-blue-500"></i> Data Transaksi
                </h4>
                <a href="{{ route('transactions.export', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>

            </div>

            <!-- Filter Form -->
            <div class="bg-gray-50 px-6 py-4">
                <form method="GET" action="{{ route('transactions.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div class="flex gap-2 items-end col-span-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex-1">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('transactions.index') }}"
                            class="px-4 py-2 border border-gray-300 text-gray-600 rounded hover:bg-gray-100">
                            <i class="fas fa-sync-alt mr-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            @if ($transactions->count())
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-6 py-6">
                    <div class="bg-blue-100 text-blue-800 p-4 rounded-xl shadow-sm">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm">Total Transaksi</p>
                                <h4 class="text-xl font-semibold">{{ $transactions->total() }}</h4>
                            </div>
                            <i class="fas fa-receipt text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-green-100 text-green-800 p-4 rounded-xl shadow-sm">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm">Total Pendapatan</p>
                                <h4 class="text-xl font-semibold">Rp
                                    {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</h4>
                            </div>
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-cyan-100 text-cyan-800 p-4 rounded-xl shadow-sm">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm">Rata-rata</p>
                                <h4 class="text-xl font-semibold">Rp
                                    {{ number_format($transactions->avg('total_amount'), 0, ',', '.') }}</h4>
                            </div>
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>

                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-xl shadow-sm">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm">Periode</p>
                                <h4 class="text-base font-semibold">
                                    @if (request('start_date') && request('end_date'))
                                        {{ date('d M Y', strtotime(request('start_date'))) }} -
                                        {{ date('d M Y', strtotime(request('end_date'))) }}
                                    @else
                                        Semua Data
                                    @endif
                                </h4>
                            </div>
                            <i class="fas fa-calendar text-2xl"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabel Transaksi -->
            <div class="px-6 pb-6 overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Invoice</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Kasir</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-right">Bayar</th>
                            <th class="px-4 py-3 text-right">Kembali</th>
                            <th class="px-4 py-3 text-center">Items</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse ($transactions as $i => $trx)
                            <tr>
                                <td class="px-4 py-2">{{ $transactions->firstItem() + $i }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 bg-gray-200 rounded text-xs">{{ $trx->invoice_code }}</span>
                                </td>
                                <td class="px-4 py-2">{{ $trx->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-2">{{ $trx->user->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-right text-green-600 font-bold">Rp
                                    {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($trx->amount_paid, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-right">
                                    @if ($trx->change > 0)
                                        <span class="text-green-600 text-xs">Rp
                                            {{ number_format($trx->change, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span
                                        class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">{{ $trx->details->count() }}
                                        items</span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('transactions.show', $trx->id) }}"
                                            class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-6 text-gray-500">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-2">Tidak ada data transaksi ditemukan</p>
                                    @if (request('start_date') || request('end_date'))
                                        <a href="{{ route('transactions.index') }}"
                                            class="text-blue-600 underline text-sm">Tampilkan Semua Data</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="flex justify-between items-center px-6 pb-6 text-sm text-gray-600">
                    <div>
                        Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari
                        {{ $transactions->total() }} data
                    </div>
                    <div>
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
