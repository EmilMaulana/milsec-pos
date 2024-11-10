@extends('layouts.admin')

@section('main-content')
    @livewire('store.index')

    @if(!auth()->user()->hasStore())
        @livewire('store.create')
    @endif

    @if (session()->has('message'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('message') }}',
                    confirmButtonText: ' OK ',
                    customClass: {
                        confirmButton: 'btn btn-success',
                    },
                    buttonsStyling: false,
                });
            });
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error', // Ubah ikon menjadi error
                    title: 'Terjadi Kesalahan!',
                    text: '{{ session('error') }}', // Tampilkan pesan error
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                    },
                    buttonsStyling: false,
                });
            });
        </script>
    @endif
@endsection
