<div>
    <div class="card shadow mb-5">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">Buat Toko Baru</h6>
        </div>
        <div class="card-body px-4">
            <form wire:submit.prevent="store">
                <!-- Nama Toko -->
                <div class="form-group mb-4">
                    <label for="storeName" class="font-weight-bold">Nama Toko</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light"><i class="fas fa-store"></i></span>
                        </div>
                        <input type="text" id="storeName" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama toko">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi Toko -->
                <div class="form-group mb-4">
                    <label for="storeDescription" class="font-weight-bold">Deskripsi Toko</label>
                    <textarea id="storeDescription" wire:model="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Tuliskan deskripsi toko"></textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Alamat Toko -->
                <div class="form-group mb-4">
                    <label for="storeAddress" class="font-weight-bold">Alamat Toko</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <textarea id="storeAddress" wire:model="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Masukkan alamat toko"></textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
                <!-- Nomor Telepon -->
                <div class="form-group mb-4">
                    <label for="storePhone" class="font-weight-bold">Nomor Telepon</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="text" id="storePhone" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Masukkan nomor telepon toko">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
                <!-- Tombol Submit -->
                <div class="form-group d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Toko</button>
                </div>
            </form>
        </div>
    </div>
</div>
