<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        $exportData = collect();

        foreach ($this->transactions as $transaction) {
            if ($transaction->details->isEmpty()) {
                $exportData->push([
                    'transaction' => $transaction,
                    'detail' => null
                ]);
            } else {
                foreach ($transaction->details as $detail) {
                    $exportData->push([
                        'transaction' => $transaction,
                        'detail' => $detail
                    ]);
                }
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Kode Invoice',
            'Kasir',
            'Tanggal',
            'Nama Produk',
            'Qty',
            'Harga Satuan',
            'Subtotal',
            'Total Harga',
            'Jumlah Bayar',
            'Kembalian',
            'Catatan'
        ];
    }

    public function map($item): array
    {
        $transaction = $item['transaction'];
        $detail = $item['detail'];

        $formatRupiah = fn($angka) => 'Rp ' . number_format($angka, 0, ',', '.');

        return [
            $transaction->invoice_code,
            $transaction->user->name ?? 'Tidak Ada',
            $transaction->created_at->format('d-m-Y H:i'),
            $detail ? $detail->product->name ?? 'Produk Tidak Ditemukan' : 'Tidak Ada Produk',
            $detail ? $detail->quantity : 0,
            $detail ? $formatRupiah($detail->price_at_time) : 'Rp 0',
            $detail ? $formatRupiah($detail->quantity * $detail->price_at_time) : 'Rp 0',
            $formatRupiah($transaction->total_amount),
            $formatRupiah($transaction->amount_paid),
            $formatRupiah($transaction->change),
            $detail ? ($detail->notes ?? '-') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '2E75B6']
                ],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            'E:K' => ['alignment' => ['horizontal' => 'right']],
            'A:D' => ['alignment' => ['horizontal' => 'left']],
            'A1:K' . ($this->transactions->flatMap(fn($t) => $t->details)->count() + $this->transactions->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 27,  // Kode Invoice
            'B' => 25,  // Kasir
            'C' => 25,  // Tanggal
            'D' => 35,  // Nama Produk
            'E' => 13,  // Qty
            'F' => 35,  // Harga Satuan
            'G' => 20,  // Subtotal
            'H' => 23,  // Total Harga
            'I' => 26,  // Jumlah Bayar
            'J' => 21,  // Kembalian
            'K' => 30,  // Catatan
        ];
    }
}
