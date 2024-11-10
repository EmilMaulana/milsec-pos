<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ strtoupper(optional($products->first()->store)->name) }}</title>

    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        /* Font Family */
        body {
            font-family: 'Poppins', sans-serif; /* Mengatur font utama halaman menjadi 'Poppins' dengan fallback 'sans-serif' */
        }

        /* Center the label body on the page */
        .label_body {
            width: 8in; /* Lebar kontainer keseluruhan diatur menjadi 8 inci */
            margin: 0 auto; /* Memposisikan kontainer di tengah secara horizontal */
            display: flex; /* Mengaktifkan Flexbox untuk layout yang fleksibel */
            flex-wrap: wrap; /* Mengizinkan elemen label untuk berpindah ke baris baru jika tidak muat dalam satu baris */
            justify-content: flex-start; /* Menyusun label dari kiri ke kanan */
        }

        /* Label Box Styling */
        .label_5160 {
            width: 2.025in; /* Lebar tiap label diatur menjadi 2.025 inci */
            height: .875in; /* Tinggi tiap label diatur menjadi 0.875 inci */
            padding: .125in .3in 0; /* Memberi padding: 0.125 inci atas, 0.3 inci kiri dan kanan, dan 0 untuk bawah */
            margin: .125in; /* Memberikan jarak antar label */
            text-align: center; /* Menyelaraskan teks di tengah label */
            overflow: hidden; /* Menyembunyikan konten yang melebihi batas label */
            outline: 1px dotted #ccc; /* Memberi garis tepi putus-putus berwarna abu-abu muda pada label */
            display: flex; /* Mengaktifkan Flexbox dalam setiap label */
            flex-direction: column; /* Menyusun elemen di dalam label secara vertikal */
            justify-content: space-between; /* Mengatur ruang antara elemen agar tersebar dari atas ke bawah */
            align-items: center; /* Menyelaraskan elemen di tengah secara horizontal */
            border-radius: 4px; /* Membuat sudut label melengkung dengan radius 4px */
        }

        /* Barcode Styling */
        .barcode-item img {
            width: 30px; /* Lebar gambar barcode diatur menjadi 30px */
            height: auto; /* Tinggi gambar disesuaikan otomatis sesuai aspek rasio */
            margin-top: 5px; /* Memberi jarak 5px di atas gambar barcode */
        }

        /* Text Styling for Product Name */
        .product-name {
            font-size: 12px; /* Ukuran font nama produk diatur menjadi 12px */
            font-weight: 700; /* Font tebal dengan bobot 700 */
            margin: 0; /* Menghilangkan margin default di sekitar teks */
            text-align: center; /* Menyelaraskan teks di tengah */
            width: 100%; /* Lebar teks mencakup 100% dari lebar label */
            color: #333; /* Warna teks abu-abu gelap (#333) */
        }

        /* Text Styling for Product Price */
        .product-price {
            font-size: 20px; /* Ukuran font harga produk diatur menjadi 20px */
            font-weight: 700; /* Font tebal dengan bobot 700 */
            margin: 0; /* Menghilangkan margin default di sekitar teks */
            color: #333; /* Warna teks abu-abu gelap (#333) */
            text-align: center; /* Menyelaraskan teks di tengah */
            width: 100%; /* Lebar teks mencakup 100% dari lebar label */
        }

        /* Text Styling for Product Code */
        .product-code {
            font-size: 10px; /* Ukuran font kode produk diatur menjadi 10px */
            margin: 0; /* Menghilangkan margin default di sekitar teks */
            color: #777; /* Warna teks abu-abu muda (#777) */
            text-align: center; /* Menyelaraskan teks di tengah */
            width: 100%; /* Lebar teks mencakup 100% dari lebar label */
        }

    </style>
</head>

<body>
    <div class="container label_body">
        <div id="sample_barcode_script" class="tab-pane">
            @foreach($products as $product)
                <div class="label_5160">
                    <div class="product-name">{{ strtoupper($product->name) }}</div>
                    <div class="product-price">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                    <div class="barcode-item">
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, 'C39') }}" alt="barcode" />
                    </div>
                    <div class="product-code">{{ $product->id_barang }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>