@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-sm text-gray-500 mb-1">Total Transaksi</h2>
            <p class="text-2xl font-bold text-green-700">{{ $summary['total_transactions'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-sm text-gray-500 mb-1">Total Pendapatan</h2>
            <p class="text-2xl font-bold text-green-700">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-sm text-gray-500 mb-1">Transaksi Hari Ini</h2>
            <p class="text-2xl font-bold text-green-700">{{ $summary['today_transactions'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-sm text-gray-500 mb-1">Pendapatan Hari Ini</h2>
            <p class="text-2xl font-bold text-green-700">Rp {{ number_format($summary['today_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Transaksi 7 Hari Terakhir</h2>
        <canvas id="transactionsChart" width="400" height="150"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('transactionsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'), $chartLabels)) !!},
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($chartData) !!},
                    borderColor: 'rgba(34,197,94,1)', // hijau tailwind
                    backgroundColor: 'rgba(34,197,94,0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
@endsection
