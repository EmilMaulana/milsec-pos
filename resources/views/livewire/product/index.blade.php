<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DAFTAR PRODUK</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Cari produk..." />
                </div>
                <div class="col-12 col-md-8">
                    <div class="d-block justify-content-between flex-wrap">
                        <a href="{{ route('product.create') }}" class="btn btn-primary m-1 mb-md-0">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                        <button wire:click="export" class="btn btn-success m-1 mb-md-0">
                            <i class="fas fa-upload"></i> Export Produk
                        </button>
                        <!-- Tombol untuk membuka modal import -->
                        <button type="button" class="btn btn-danger m-1 mb-md-0" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-download"></i>
                            Import Produk
                        </button>
                        <button wire:click="exportBarcode" class="btn btn-warning m-1 mb-md-0">
                            <i class="fas fa-upload"></i> Export Barcode
                        </button>
                    </div>
                </div>
            </div>
            
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead">
                        <tr class="text-center">
                            <th>No</th>
                            <th>ID Barang</th>
                            <th>Nama</th>
                            <th>Harga Dasar</th>
                            <th>Harga Jual</th>
                            <th>Diskon</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $index => $product)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->id_barang ?? 'Tidak Tersedia' }}</td>
                                <td>{{ $product->name }}</td>
                                <td>Rp. {{ number_format($product->base_price) }}</td>
                                <td>Rp. {{ number_format($product->sell_price) }}</td>
                                <td>{{ $product->disc }}%</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ ucwords($product->unit) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('product.edit', $product->slug) }}" class="btn btn-sm btn-warning m-1">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger m-1" data-toggle="modal" data-target="#deleteModal{{ $product->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info m-1" data-toggle="modal" data-target="#barcodeModal{{ $product->id }}">
                                        <i class="fas fa-barcode"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Modal Konfirmasi Hapus -->
                            <div wire:ignore.self class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Konfirmasi Hapus</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus produk "{{ $product->name }}"?
                                            <br>
                                            <small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-danger" wire:click="delete('{{ $product->slug }}')" data-dismiss="modal">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div wire:ignore.self class="modal fade" id="barcodeModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="barcodeModalLabel{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title text-center w-100" id="barcodeModalLabel{{ $product->id }}">
                                                {{ $product->store->name }} <!-- Nama toko, bisa diambil dari konfigurasi -->
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <!-- Nama Produk -->
                                            <h5 class="font-weight-bold mb-3">{{ $product->name }}</h5>
                            
                                            <!-- Harga Produk -->
                                            <h4 class="font-weight-bold text-danger mb-4">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</h4>
                            
                                            <!-- Menampilkan Barcode -->
                                            <div class="border p-2 d-inline-block mb-3">
                                                <img src="{{ route('product.barcode', $product->id) }}" alt="Barcode" class="img-fluid" style="max-width: 100%;" />
                                            </div>
                            
                                            <!-- Kode Produk atau SKU -->
                                            <p class="text-muted small mb-0">Kode Produk: {{ $product->id_barang ?? $product->id }}</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            {{-- <a href="{{ route('product.barcode.download', $product->id) }}" class="btn btn-info">
                                                <i class="fas fa-download"></i> Download Label
                                            </a> --}}
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada produk ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if (!$search) <!-- Hanya tampilkan paginasi jika tidak ada pencarian -->
                    {{ $products->links() }} <!-- Pagination -->
                @endif
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Silakan unduh template untuk mengimpor produk dan isi sesuai dengan format yang ditentukan.</p>
                    <a href="{{ route('product.template') }}" class="btn btn-info mb-2"><i class="fas fa-download"></i> Download</a>
                    <input type="file" wire:model="file" class="form-control mb-2" />
                    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" class="close" data-dismiss="modal" aria-label="Close">Tutup</button>
                    <button wire:click="import" class="btn btn-danger">Impor Produk</button>
                </div>
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