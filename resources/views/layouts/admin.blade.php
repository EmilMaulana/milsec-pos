<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Laravel SB Admin 2">
    <meta name="author" content="Alejandro RH">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'CASH BRO' }}</title>

    @livewireStyles
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Bootstrap CSS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle with Popper.js (JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png">
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/home') }}">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">
                <h4 class="pt-2 font-weight-bold">CASH BRO </h4>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Nav::isRoute('home') }}">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>{{ __('Dashboard') }}</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            {{ __('Menu') }}
        </div>

        @if(auth()->check())
            @if(auth()->user()->role === 'staff')
                <!-- Tampilkan hanya menu Transaksi untuk pengguna dengan role staff -->
                <li class="nav-item {{ Nav::isRoute('transaction.index') }}">
                    <a class="nav-link" href="{{ route('transaction.index') }}">
                        <i class="fa-solid fa-cash-register"></i>
                        <span>{{ __('Transaksi') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Nav::isRoute('transaction.history') }}">
                    <a class="nav-link" href="{{ route('transaction.history') }}">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>{{ __('Riwayat Transaksi') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Nav::isRoute(['cashflow.index', 'cashflow.edit', 'cashflow.create']) }}">
                    <a class="nav-link" href="{{ route('cashflow.index') }}">
                        <i class="fa-solid fa-note-sticky"></i>
                        <span>{{ __('Arus Kas') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Nav::isRoute(['attendance.index', 'attendance.create']) }}">
                    <a class="nav-link" href="{{ route('attendance.index') }}">
                        <i class="fa-solid fa-user-check"></i>
                        <span>{{ __('Absensi') }}</span>
                    </a>
                </li>                
            @elseif(auth()->user()->hasStore() && auth()->user()->role === 'owner')
                <!-- Tampilkan semua menu untuk pengguna dengan role owner -->
                <li class="nav-item {{ Nav::isRoute('transaction.index') }}">
                    <a class="nav-link" href="{{ route('transaction.index') }}">
                        <i class="fa-solid fa-cash-register"></i>
                        <span>{{ __('Transaksi') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Nav::isRoute('transaction.history') }}">
                    <a class="nav-link" href="{{ route('transaction.history') }}">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>{{ __('Riwayat Transaksi') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Nav::isRoute(['cashflow.index', 'cashflow.edit', 'cashflow.create']) }}">
                    <a class="nav-link" href="{{ route('cashflow.index') }}">
                        <i class="fa-solid fa-note-sticky"></i>
                        <span>{{ __('Arus Kas') }}</span>
                    </a>
                </li>
                <li class="nav-item {{ Nav::isRoute(['attendance.index', 'attendance.create']) }}">
                    <a class="nav-link" href="{{ route('attendance.index') }}">
                        <i class="fa-solid fa-user-check"></i>
                        <span>{{ __('Absensi') }}</span>
                    </a>
                </li>
                
                <li class="nav-item {{ Nav::isRoute(['product.index', 'product.edit', 'staff.index']) }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#manajemen"
                        aria-expanded="true" aria-controls="manajemen">
                        <i class="fa-solid fa-sliders"></i>
                        <span>{{ __('Manajemen') }}</span>
                    </a>
                    <div id="manajemen" class="collapse" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item {{ Nav::isRoute(['product.index', 'product.edit']) }}" href="{{ route('product.index') }}">Produk</a>
                            <a class="collapse-item {{ Nav::isRoute(['staff.index', 'staff.edit']) }}" href="{{ route('staff.index') }}">Staff</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item {{ Nav::isRoute(['report.transaction', 'report.modal']) }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fa-solid fa-address-book"></i>
                        <span>{{ __('Laporan') }}</span>
                    </a>
                    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item {{ Nav::isRoute('report.transaction') }}" href="{{ route('report.transaction') }}">Data Transaksi</a>
                            <a class="collapse-item {{ Nav::isRoute('report.modal') }}" href="{{ route('report.modal') }}">Data Modal</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item {{ Nav::isRoute(['store.index', 'store.edit']) }}">
                    <a class="nav-link" href="{{ route('store.index') }}">
                        <i class="fa-solid fa-cash-register"></i>
                        <span>{{ __('Toko') }}</span>
                    </a>
                </li>
            @endif
        @endif
        @if (auth()->check() && is_null(auth()->user()->store_id))
            <li class="nav-item {{ Nav::isRoute(['store.index', 'store.edit']) }}">
                <a class="nav-link" href="{{ route('store.index') }}">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>{{ __('Toko') }}</span>
                </a>
            </li>
        @endif





        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        <!-- Nav Item - Profile -->
        <li class="nav-item {{ Nav::isRoute('profile') }}">
            <a class="nav-link" href="{{ route('profile') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>{{ __('Profile') }}</span>
            </a>
        </li>

        <!-- Nav Item - About -->
        <li class="nav-item {{ Nav::isRoute('about') }}">
            <a class="nav-link" href="{{ route('about') }}">
                <i class="fas fa-fw fa-hands-helping"></i>
                <span>{{ __('About') }}</span>
            </a>
        </li>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>


                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Dropdown - Alerts -->
                    <div class="pt-4">
                        <button id="fullscreen-button" class="btn btn-secondary btn-sm w-100" style="border-radius: 12px;">
                            <i class="fas fa-expand" id="fullscreen-icon"></i> <span id="fullscreen-text">FULL SCREEN</span>
                        </button>
                    </div>

                    
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->fullname }}</span>
                            <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ Auth::user()->name[0] }}"></figure>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Profile') }}
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Settings') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('activity-log') }}">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Activity Log') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                @yield('main-content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <small>Dibuat dan dikembangkan oleh <a href="https://instagram.com/emilmaul_" target="_blank">Emil Maulana</a>. {{ now()->year }}</small>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
@livewireScripts


<script>
    const fullscreenButton = document.getElementById('fullscreen-button');
    const fullscreenIcon = document.getElementById('fullscreen-icon');
    const fullscreenText = document.getElementById('fullscreen-text');

    fullscreenButton.addEventListener('click', function() {
        const elem = document.documentElement; // Menggunakan elemen utama dokumen untuk memastikan kompatibilitas

        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            // Masuk ke mode layar penuh (untuk desktop dan beberapa mobile)
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) { // Safari dan beberapa mobile
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { // IE/Edge
                elem.msRequestFullscreen();
            } 
            // Ubah ikon dan teks tombol
            fullscreenIcon.classList.replace('fa-expand', 'fa-compress');
            fullscreenText.textContent = 'EXIT FULL SCREEN';
        } else {
            // Keluar dari mode layar penuh
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) { // Safari dan beberapa mobile
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { // IE/Edge
                document.msExitFullscreen();
            }
            // Ubah ikon dan teks tombol
            fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
            fullscreenText.textContent = 'FULL SCREEN';
        }
    });

    // Mengatur agar tampilan kembali normal saat keluar dari mode layar penuh
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('msfullscreenchange', handleFullscreenChange);

    function handleFullscreenChange() {
        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
            fullscreenText.textContent = 'FULL SCREEN';
        }
    }
</script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>
