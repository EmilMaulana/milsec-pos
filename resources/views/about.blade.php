@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('About') }}</h1>

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-profile-image mt-4">
                    <img src="{{ asset('img/favicon.png') }}" class="rounded-circle" alt="user-image">
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="font-weight-bold">Tentang Aplikasi POS</h5>
                            <p>Aplikasi POS (Point of Sale) ini dirancang untuk mempermudah manajemen penjualan dan inventaris bisnis Anda.</p>
                            <p>Dengan antarmuka yang intuitif dan fitur yang lengkap, aplikasi ini sangat cocok untuk usaha retail maupun restoran.</p>
                            <p>Jika Anda merasa aplikasi ini bermanfaat, pertimbangkan untuk memberikan ⭐ sebagai dukungan!</p>
                            <a href="https://github.com/your-repo-url" target="_blank" class="btn btn-github">
                                <i class="fab fa-github fa-fw"></i> Kunjungi repository
                            </a>
                        </div>
                    </div>
                
                    <hr>
                
                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="font-weight-bold">Kredit</h5>
                            <p>Aplikasi POS ini menggunakan beberapa library dan paket pihak ketiga yang bersifat open-source. Terima kasih kepada komunitas web.</p>
                            <ul>
                                <li><a href="https://laravel.com" target="_blank">Laravel</a> - Kerangka kerja open source untuk pengembangan web.</li>
                                <li><a href="https://github.com/DevMarketer/LaravelEasyNav" target="_blank">LaravelEasyNav</a> - Memudahkan pengelolaan navigasi di Laravel.</li>
                                <li><a href="https://startbootstrap.com/themes/sb-admin-2" target="_blank">SB Admin 2</a> - Tema yang digunakan untuk antarmuka.</li>
                            </ul>
                        </div>
                    </div>
                
                </div>
                
            </div>

        </div>

    </div>

@endsection
