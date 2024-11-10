<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Toko Anda</h6>
        </div>
        <div class="card-body">
            @if ($stores->isEmpty())
                <div class="alert alert-info">
                    Anda belum memiliki toko. Silakan buat toko baru dengan mengisi form dibawah.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Nama Toko</th>
                                <th>Deskripsi</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach($stores as $index => $store)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->description }}</td>
                                    <td>{{ $store->address }}</td>
                                    <td>{{ $store->phone }}</td>
                                    <td>
                                        <a href="{{ route('store.edit', $store->slug) }}" class="btn btn-warning btn-sm m-1"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#deleteModal{{ $store->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal Konfirmasi Hapus -->
                                <div wire:ignore.self class="modal fade" id="deleteModal{{ $store->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $store->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $store->id }}">Konfirmasi Penghapusan Toko</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Anda akan menghapus toko <strong>"{{ $store->name }}"</strong> beserta semua data yang terkait, termasuk produk, transaksi, dan informasi lainnya.</p>
                                                <p><strong>Apakah Anda yakin ingin melanjutkan tindakan ini?</strong></p>
                                                <small class="text-danger">Tindakan ini tidak dapat dibatalkan dan semua data yang dihapus akan hilang selamanya.</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#validationModal{{ $store->slug }}" data-dismiss="modal">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Konfirmasi Validasi -->
                                <div wire:ignore.self class="modal fade" id="validationModal{{ $store->slug }}" tabindex="-1" role="dialog" aria-labelledby="validationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="validationModalLabel">Validasi Penghapusan Toko</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Untuk melanjutkan penghapusan, silakan masukkan email dan password Anda.</p>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" wire:model="email" id="email" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" wire:model="password" id="password" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-danger" wire:click="confirmDelete('{{ $store->slug }}')"  data-dismiss="modal">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
