<div>
    {{-- Success is as dangerous as failure. --}}
    <h1 class="h3 mb-4 text-gray-800">Riwayat Arus Kas</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Arus Kas</h6>
        </div>
        <div class="card-body">
            <div class="my-2">
                <a href="{{ route('cashflow.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
            </div>
            <div class="table-responsive overflow-x-auto">
                <table class="table table-bordered text-center text-nowrap" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Saldo Awal</th>
                            <th>Jumlah</th>
                            <th>Type</th>
                            <th>Saldo Akhir</th>
                            <th>Deskripsi</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cashflows as $index => $cashflow)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cashflow->user->fullname }}</td>
                                <td>Rp {{ number_format($cashflow->starting_balance, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($cashflow->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($cashflow->type === 'income')
                                        <span class="badge badge-success py-2 px-2">Pemasukan</span>
                                    @else
                                        <span class="badge badge-danger py-2 px-2">Pengeluaran</span>
                                    @endif
                                </td>                                
                                <td>Rp {{ number_format($cashflow->ending_balance, 0, ',', '.') }}</td>
                                <td>{{ $cashflow->description }}</td>
                                <td>{{ $cashflow->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $cashflows->links() }}
            </div>
        </div>
    </div>
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
</div>
