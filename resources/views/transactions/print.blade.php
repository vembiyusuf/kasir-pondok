<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran - PPBA Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            body {
                width: 76mm;
                margin: 0 auto;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .receipt-container {
                box-shadow: none;
            }
        }

        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            padding: 16px 0;
        }

        .receipt-container {
            width: 76mm;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .receipt-header {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 16px 12px;
            position: relative;
            overflow: hidden;
        }

        .receipt-header::after {
            content: "";
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background: white;
            border-radius: 50%;
            filter: blur(10px);
        }

        .store-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
            margin: 12px 0;
        }

        .item-row {
            transition: all 0.2s ease;
        }

        .item-row:hover {
            background-color: #f8fafc;
            transform: translateX(2px);
        }

        .total-section {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #e2e8f0;
        }

        .payment-method {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .cash {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .card {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .qr {
            background-color: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .footer-note {
            position: relative;
            padding-top: 16px;
        }

        .footer-note::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40%;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #10b981, #3b82f6);
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header Section -->
        <div class="receipt-header">
            <div class="store-logo">
                <i class="fas fa-store-alt text-3xl mr-3"></i>
                <h1 class="text-2xl font-bold tracking-tight">PPBA MARKET</h1>
            </div>
            <div class="text-center text-xs opacity-90">
                <p><i class="fas fa-map-marker-alt mr-1"></i> Jl. Contoh No. 123, Kota Contoh</p>
                <p><i class="fas fa-phone-alt mr-1"></i> 0812 3456 789 | ppba@market.com</p>
                <p class="mt-1 text-[11px] opacity-80">Buka Setiap Hari 08:00 - 22:00</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-4">
            <!-- Transaction Info -->
            <div class="bg-white rounded-lg mb-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-2">
                            <i class="fas fa-receipt text-blue-600"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Nomor Invoice</div>
                            <div class="font-bold text-blue-600">{{ $transaction->invoice_code }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Tanggal</div>
                        <div class="font-medium">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-2">
                            <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Kasir</div>
                            <div class="font-medium">{{ $transaction->user->name }}</div>
                        </div>
                    </div>
                    @if ($transaction->customer_name)
                        <div class="flex items-center col-span-2">
                            <div class="bg-blue-100 p-2 rounded-lg mr-2">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Pelanggan</div>
                                <div class="font-medium">{{ $transaction->customer_name }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="divider"></div>

            <!-- Items List -->
            <div class="mb-4">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left pb-2 text-xs text-gray-500 font-normal">PRODUK</th>
                            <th class="text-right pb-2 w-10 text-xs text-gray-500 font-normal">QTY</th>
                            <th class="text-right pb-2 w-16 text-xs text-gray-500 font-normal">HARGA</th>
                            <th class="text-right pb-2 w-16 text-xs text-gray-500 font-normal">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->details as $detail)
                            <tr class="item-row">
                                <td class="py-2">
                                    <div class="font-medium text-sm">{{ $detail->product->name }}</div>
                                    @if ($detail->serving_type)
                                        <div class="text-xs text-gray-500 mt-1 italic">{{ $detail->serving_type }}</div>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-1">
                                        @ Rp{{ number_format($detail->price_at_time, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="text-right align-top text-sm">{{ $detail->quantity }}</td>
                                <td class="text-right align-top text-sm">
                                    {{ number_format($detail->price_at_time, 0, ',', '.') }}
                                </td>
                                <td class="text-right align-top font-medium text-sm">
                                    Rp{{ number_format($detail->quantity * $detail->price_at_time, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>

            <!-- Payment Summary -->
            <div class="total-section">
                <div class="flex justify-between items-center py-1">
                    <span class="text-sm">Subtotal Produk</span>
                    <span class="font-medium">Rp{{ number_format($transaction->subtotal_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-1 text-red-500">
                    <span class="text-sm flex items-center">
                        <i class="fas fa-tag mr-2 text-sm"></i> Diskon
                    </span>
                    <span class="font-medium">-
                        Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                </div>

                <div class="divider my-2"></div>

                <div class="flex justify-between items-center py-2">
                    <span class="font-bold text-base">TOTAL</span>
                    <span class="font-bold text-lg text-blue-600">
                        Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="divider my-2"></div>

                <div class="flex justify-between items-center py-1 text-green-600">
                    <span class="text-sm flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i> Bayar
                        <span class="payment-method {{ strtolower($transaction->payment_method) }} ml-2">
                            {{ strtoupper($transaction->payment_method) }}
                        </span>
                    </span>
                    <span class="font-medium">Rp{{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-sm flex items-center">
                        <i class="fas fa-exchange-alt mr-2 text-gray-500"></i> Kembalian
                    </span>
                    <span class="font-medium text-gray-700">
                        Rp{{ number_format($transaction->change, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="footer-note text-center mt-6">
                <div class="bg-blue-50 rounded-lg px-3 py-2 mb-3 inline-block max-w-full">
                    <p class="text-xs text-gray-600 leading-tight">
                        <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                        Barang yang sudah dibeli tidak dapat ditukar/dikembalikan
                    </p>
                </div>
                <p class="text-sm text-gray-600 mb-1">Terima kasih telah berbelanja</p>
                <p class="text-xs text-gray-400 italic">~ Semoga hari Anda menyenangkan ~</p>

                <div class="mt-4 text-[10px] text-gray-400">
                    <p>Struk ini merupakan bukti pembayaran yang sah</p>
                    <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Buttons (Non-printable) -->
    <div class="no-print fixed bottom-4 left-0 right-0 flex justify-center space-x-4">
        <button onclick="window.print()"
            class="px-5 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 text-sm font-bold shadow-lg transition-all flex items-center">
            <i class="fas fa-print mr-2"></i> Cetak Struk
        </button>
        <button onclick="window.close()"
            class="px-5 py-3 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 text-sm font-bold shadow-lg transition-all flex items-center">
            <i class="fas fa-times mr-2"></i> Tutup
        </button>
    </div>

    <script>
        // Auto print and close after 3 seconds if on mobile
        if (window.innerWidth < 768) {
            setTimeout(() => {
                window.print();
                setTimeout(() => window.close(), 1000);
            }, 3000);
        }
    </script>
</body>

</html>
