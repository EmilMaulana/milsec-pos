<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Modal Barang</h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="m-0 font-weight-bold">Total Modal:</h6>
                <div class="col ml-2 h4 fw-bold">
                    Rp. {{ number_format($totalModal, 2, ',', '.') }}
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Cari Data Modal" wire:model.live="search" /> <!-- Menghubungkan input dengan Livewire -->
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga Dasar</th>
                            <th>Stok</th>
                            <th>Sisa Modal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data modal barang.</td>
                            </tr>
                        @else
                            @foreach($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="d-flex align-items-center">
                                        <div class="icon-inisial rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px; background-color: #bac6d4; color: white;">
                                            {{ strtoupper(substr($product->name, 0, 2)) }} <!-- Menampilkan huruf pertama dari nama barang -->
                                        </div>
                                        <span>{{ $product->name }}</span> <!-- Menggunakan span untuk nama barang -->
                                    </td>
                                    <td>Rp. {{ number_format($product->base_price, 2) }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>Rp. {{ number_format($product->base_price * $product->stock, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Paginasi -->
            {{ $products->links() }} <!-- Menampilkan link paginasi -->
        </div>
    </div>
</div>
