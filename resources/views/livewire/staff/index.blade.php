<div>
    <div class="mb-4">
        <h2 class="text-lg font-semibold mb-4">Manajemen Staff</h2>
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Staff</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 ">
                        <input type="text" wire:model.live="search" placeholder="Cari staff..." class="form-control" />
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $index => $member)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $member->fullname }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>
                                        <div class="">
                                            <a href="{{ route('staff.edit', $member->id)}}" class="btn btn-warning btn-sm m-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#deleteModal{{ $member->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Konfirmasi Hapus -->
                                <div wire:ignore.self class="modal fade" id="deleteModal{{ $member->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $member->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $member->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah kamu yakin ingin menghapus staff {{ $member->fullname }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-danger" wire:click="delete({{ $member->id }})" data-dismiss="modal">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data staff tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $staff->links() }} <!-- Untuk pagination -->
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Staff</h6>
            </div>
        
            <div class="card-body">
                <form wire:submit.prevent="store">
                    <h6 class="heading-small text-muted mb-4">Informasi Staff</h6>
                
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="name">Nama Depan<span class="small text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control" wire:model="name" placeholder="Nama Depan">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="last_name">Nama Belakang<span class="small text-danger">*</span></label>
                                    <input type="text" id="last_name" class="form-control" wire:model="last_name" placeholder="Nama Belakang">
                                    @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="email">Email<span class="small text-danger">*</span></label>
                                    <input type="email" id="email" class="form-control" wire:model="email" placeholder="example@example.com">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="password">Password</label>
                                    <input type="password" id="password" class="form-control" wire:model="password" placeholder="Password">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="phone">Phone<span class="small text-danger">*</span></label>
                                    <input type="text" id="phone" class="form-control" wire:model="phone" placeholder="0812xxxxxxxx">
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group focused">
                                    <label class="form-control-label" for="store_id">Toko<span class="small text-danger">*</span></label>
                                    <input type="text" disabled id="store_id" class="form-control" placeholder="Toko" value="{{ $this->store->name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="password_confirmation">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" class="form-control" wire:model="password_confirmation" placeholder="Konfirmasi Password">
                                    @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>                         
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
