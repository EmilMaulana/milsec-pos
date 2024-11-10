<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
        }
        .receipt-container {
            max-width: 320px;
            margin: 20px auto;
            padding: 20px;
            border: 1px dashed #000;
        }
        .store-name {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .receipt-header, .receipt-footer {
            text-align: center;
            font-size: 14px;
        }
        .receipt-title {
            font-weight: bold;
            font-size: 18px;
        }
        .receipt-item {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }
        .total-amount {
            font-weight: bold;
        }
        @media print {
            @page {
                size: 80mm auto; /* Menyesuaikan dengan ukuran kertas thermal */
                margin: 0; /* Menghilangkan margin default */
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    
    <div class="receipt-container">
        <!-- Nama Toko -->
        <div class="receipt-header">
            <span class="fw-bold h4">{{ Auth::user()->store->name ?? '-' }}</span>
            <br class="pt-4">
            <small >{{ $transactionReceipt['transaction_id'] }}</small>
            <br>
            <small>{{ now()->format('d-m-Y H:i') }}</small>
            <br>
            <small>{{ Auth::user()->store->phone ?? '-' }}</small>
        </div>
        
        <hr>

        <!-- Daftar Item -->
        <div class="receipt-body">
            <small class="mb-1 fw-bold">Items:</small>
            <ul class="list-unstyled mb-3">
                @foreach ($transactionReceipt['items'] as $item)
                    <li class="receipt-item">
                        <span>{{ $item['product']['name'] }} x {{ $item['quantity'] }}</span>
                        <span>Rp. {{ number_format($item['product']['sell_price'] * $item['quantity'], 2) }}</span>
                    </li>
                @endforeach
            </ul>

            <!-- Total dan Informasi Pembayaran -->
            <hr>
            <div class="receipt-item">
                <span>Total</span>
                <span class="total-amount">Rp. {{ number_format($transactionReceipt['total_price'], 2) }}</span>
            </div>
            <div class="receipt-item">
                <span>Metode Pembayaran</span>
                <span>{{ ucwords($transactionReceipt['payment_method']) }}</span>
            </div>
            <div class="receipt-item">
                <span>Jumlah Dibayar</span>
                <span>Rp. {{ number_format($transactionReceipt['payment_amount'], 2) }}</span>
            </div>
            <div class="receipt-item">
                <span>Kembalian</span>
                <span>Rp. {{ number_format($transactionReceipt['change_amount'], 2) }}</span>
            </div>
        </div>

        <hr>

        <!-- Pesan Penutup -->
        <div class="receipt-footer">
            <span class="mt-2">Terima Kasih atas Kunjungan Anda!</span>
            <span>Silakan datang kembali</span>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print(); // Memicu pencetakan otomatis
            window.onafterprint = function() {
                window.location.href = "{{ route('transaction.index') }}"; // Redirect setelah cetak
            };
        };
    </script>
</body>
</html>
