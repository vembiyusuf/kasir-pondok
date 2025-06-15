<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data transaksi 7 hari terakhir (termasuk hari ini)
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Data transaksi per hari
        $transactionsPerDay = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        // Siapkan array tanggal 7 hari terakhir
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // Lengkapi data transaksi agar semua tanggal ada
        $chartData = collect($dates)->map(function ($date) use ($transactionsPerDay) {
            return $transactionsPerDay->get($date, 0);
        });

        // Ringkasan penjualan
        $today = Transaction::whereDate('created_at', today())->get();
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('total_amount'),
            'today_transactions' => $today->count(),
            'today_revenue' => $today->sum('total_amount'),
        ];

        return view('dashboard', [
            'transactions' => $transactions,
            'summary' => $summary,
            'chartLabels' => $dates,
            'chartData' => $chartData,
        ]);
    }
}
