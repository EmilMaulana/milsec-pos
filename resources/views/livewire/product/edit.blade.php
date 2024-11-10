<div>
    {{-- Do your work, then step back. --}}
    <div class="container">
        <h1 class="h3 mb-4 text-gray-800">{{ __('Update Produk') }}</h1>
        <a href="{{ route('product.index') }}" class="btn btn-primary my-2"><i class="fas fa-arrow-left"></i> Kembali</a>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form wire:submit.prevent="update">
                    <!-- Nama Produk -->
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" wire:model="name" placeholder="Masukkan nama produk">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="id_barang">ID Produk</label>
                        <input type="text" id="id_barang" class="form-control @error('id_barang') is-invalid @enderror" wire:model="id_barang" placeholder="Masukkan nama produk">
                        @error('id_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Harga Produk -->
                    <div class="form-group">
                        <label for="price">Harga Dasar</label>
                        <input type="number" id="base_price" class="form-control @error('base_price') is-invalid @enderror" wire:model="base_price" placeholder="Masukkan harga produk">
                        @error('base_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga Produk -->
                    <div class="form-group">
                        <label for="price">Harga Jual</label>
                        <input type="number" id="sell_price" class="form-control @error('sell_price') is-invalid @enderror" wire:model="sell_price" placeholder="Masukkan harga produk">
                        @error('sell_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga Produk -->
                    <div class="form-group">
                        <label for="price">Diskon</label>
                        <input type="number" id="disc" class="form-control @error('disc') is-invalid @enderror" wire:model="disc" placeholder="Masukkan harga produk">
                        @error('disc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Satuan Produk -->
                    <div class="form-group">
                        <label for="unit">Satuan</label>
                        <select id="unit" wire:model='unit' class="form-select @error('unit') is-invalid @enderror">
                            <option value="pcs">Pcs</option>
                            <option value="lusin">Lusin</option>
                            <option value="pack">Pack</option>
                            <option value="bal">Bal</option>
                            <option value="karton">Karton</option>
                        </select>
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stok Produk -->
                    <div class="form-group">
                        <label for="stock">Stok Produk</label>
                        <input type="number" id="stock" class="form-control @error('stock') is-invalid @enderror" wire:model="stock" placeholder="Masukkan jumlah stok produk">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary ">
                        <i class="fas fa-save"></i> Update Produk
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</div>
