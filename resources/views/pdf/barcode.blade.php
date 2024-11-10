<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Label Product Info</title>
    <!-- Sertakan Bootstrap dari CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @page {
            size: 2.1in 1.2in; /* Ukuran label 2.1 x 1.2 inci */
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4px;
            box-sizing: border-box;
        }

        .product-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
            text-align: center;
        }

        .product-code {
            font-size: 9px;
            color: #444;
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .barcode {
            text-align: center;
            margin-top: 5px;
        }

        .barcode img {
            width: 100%; /* Mengatur ukuran gambar barcode agar muat */
            max-width: 100px; /* Lebar maksimum barcode */
        }

        .price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
            font-size: 10px;
        }

        .price-left {
            font-size: 10px;
            color: #333;
        }

        .price {
            font-size: 12px;
            font-weight: bold;
            color: #e74c3c;
            text-align: right;
            margin-right: 30px;
        }

        .additional-info {
            font-size: 8px;
            color: #666;
            text-align: right;
            margin-top: 2px;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Informasi Produk -->
        <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-code">
                <span>{{ $product->id_barang }}</span>
            </div>
            <div class="barcode">
                <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
            </div>
        </div>

        <!-- Bagian Harga -->
        <div class="price-section">
            <div class="price-left">Harga Normal:</div>
            <h4 class="price">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</h4> 
            
        </div>
    </div>

    <!-- Sertakan JavaScript dari Bootstrap (Opsional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
