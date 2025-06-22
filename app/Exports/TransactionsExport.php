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
    protected $transactionGroups = [];

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        $exportData = collect();
        $rowIndex = 2; // Start from row 2 (after header)

        foreach ($this->transactions as $transaction) {
            $startRow = $rowIndex;

            if ($transaction->details->isEmpty()) {
                $exportData->push([
                    'transaction' => $transaction,
                    'detail' => null,
                    'is_first_row' => true
                ]);
                $rowIndex++;
            } else {
                $is_first_row = true;
                foreach ($transaction->details as $detail) {
                    $exportData->push([
                        'transaction' => $transaction,
                        'detail' => $detail,
                        'is_first_row' => $is_first_row
                    ]);
                    $is_first_row = false;
                    $rowIndex++;
                }
            }

            $this->transactionGroups[] = [
                'transaction' => $transaction,
                'start_row' => $startRow,
                'end_row' => $rowIndex - 1
            ];
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Kode Invoice',
            'Kasir',
            'Tanggal',
            'Metode Pembayaran',
            'Pelanggan & Status', // Combined column
            'Diskon',
            'Nama Produk',
            'Jenis Penyajian',
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
        $is_first_row = $item['is_first_row'];

        $formatRupiah = fn($angka) => 'Rp ' . number_format($angka, 0, ',', '.');
        $paymentMethodMap = [
            'cash' => 'Tunai',
            'card' => 'Kartu',
            'transfer' => 'Transfer'
        ];

        $paymentStatusMap = [
            'paid' => 'Lunas',
            'unpaid' => 'Tidak Lunas'
        ];

        // Combine customer name and payment status
        $customerAndStatus = $transaction->customer_name ?? '-';
        $customerAndStatus .= "\n(" . ($paymentStatusMap[$transaction->payment_status] ?? $transaction->payment_status) . ")";

        return [
            $transaction->invoice_code,
            $transaction->user->name ?? 'Tidak Ada',
            $transaction->created_at->format('d-m-Y H:i'),
            $paymentMethodMap[$transaction->payment_method] ?? $transaction->payment_method,
            $customerAndStatus, // Combined field
            $formatRupiah($transaction->discount_amount),
            $detail ? $detail->product->name ?? 'Produk Tidak Ditemukan' : 'Tidak Ada Produk',
            $detail ? ($detail->serving_type ?? 'Standar') : 'Standar',
            $detail ? $detail->quantity : 0,
            $detail ? $formatRupiah($detail->price_at_time) : 'Rp 0',
            $detail ? $formatRupiah($detail->quantity * $detail->price_at_time) : 'Rp 0',
            $is_first_row ? $formatRupiah($transaction->total_amount) : '',
            $is_first_row ? $formatRupiah($transaction->amount_paid) : '',
            $is_first_row ? $formatRupiah($transaction->change) : '',
            $detail ? ($detail->notes ?? '-') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->transactions->flatMap(fn($t) => $t->details)->count() + $this->transactions->count() + 1;

        // Apply basic styling to all cells
        $sheet->getStyle('A1:O' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Header style
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '4CAF50']
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
        ]);

        // Enable text wrapping for the combined column
        $sheet->getStyle('E2:E' . $lastRow)
            ->getAlignment()
            ->setWrapText(true);

        // Process each transaction group for merging and coloring
        $colorIndex = 0;
        $colors = ['E8F5E9', 'E3F2FD', 'FFF8E1', 'F3E5F5']; // Light green, blue, yellow, purple

        foreach ($this->transactionGroups as $group) {
            $color = $colors[$colorIndex % count($colors)];
            $colorIndex++;

            // Merge cells for transaction-level data
            $sheet->mergeCells("A{$group['start_row']}:A{$group['end_row']}");
            $sheet->mergeCells("B{$group['start_row']}:B{$group['end_row']}");
            $sheet->mergeCells("C{$group['start_row']}:C{$group['end_row']}");
            $sheet->mergeCells("D{$group['start_row']}:D{$group['end_row']}");
            $sheet->mergeCells("E{$group['start_row']}:E{$group['end_row']}"); // Combined column
            $sheet->mergeCells("F{$group['start_row']}:F{$group['end_row']}"); // Diskon

            // Merge cells for total, payment, and change
            $sheet->mergeCells("L{$group['start_row']}:L{$group['end_row']}"); // Total Harga
            $sheet->mergeCells("M{$group['start_row']}:M{$group['end_row']}"); // Jumlah Bayar
            $sheet->mergeCells("N{$group['start_row']}:N{$group['end_row']}"); // Kembalian

            // Apply alternating colors to each transaction group
            $sheet->getStyle("A{$group['start_row']}:O{$group['end_row']}")
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($color);

            // Center alignment for all merged cells
            $sheet->getStyle("A{$group['start_row']}:F{$group['end_row']}")
                ->getAlignment()
                ->setVertical('center');

            $sheet->getStyle("L{$group['start_row']}:N{$group['end_row']}")
                ->getAlignment()
                ->setVertical('center')
                ->setHorizontal('right');

            // Style for payment status in combined column
            $paymentStatusStyle = $sheet->getStyle("E{$group['start_row']}");
            $paymentStatusStyle->getFont()->setBold(true);

            // Color based on payment status
            $paymentColor = $group['transaction']->payment_status === 'paid' ? '2E7D32' : 'C62828';
            $paymentStatusStyle->getFont()->getColor()->setRGB($paymentColor);
        }

        // Alignment styles
        $sheet->getStyle('A2:F' . $lastRow)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('G2:K' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('O2:O' . $lastRow)->getAlignment()->setHorizontal('left');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 27,  // Kode Invoice
            'B' => 25,  // Kasir
            'C' => 25,  // Tanggal
            'D' => 20,  // Metode Pembayaran
            'E' => 30,  // Pelanggan & Status (wider for combined info)
            'F' => 20,  // Diskon
            'G' => 35,  // Nama Produk
            'H' => 20,  // Jenis Penyajian
            'I' => 13,  // Qty
            'J' => 35,  // Harga Satuan
            'K' => 20,  // Subtotal
            'L' => 23,  // Total Harga
            'M' => 26,  // Jumlah Bayar
            'N' => 21,  // Kembalian
            'O' => 30,  // Catatan
        ];
    }
}
