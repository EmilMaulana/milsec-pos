<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data Cashflow</h6>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="update">
                <!-- Saldo Awal (Readonly) -->
                <div class="form-group">
                    <label for="starting_balance">Saldo Awal (Pendapatan Bulanan)</label>
                    <input type="number" class="form-control" id="starting_balance" wire:model="starting_balance" readonly>
                </div>
    
                <!-- Jumlah -->
                <div class="form-group">
                    <label for="amount">Jumlah</label>
                    <input type="number" class="form-control" id="amount" wire:model="amount" placeholder="Masukkan jumlah transaksi">
                    @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
    
                <!-- Tipe Transaksi (Debet/Kredit) -->
                <div class="form-group">
                    <label for="type">Tipe Transaksi</label>
                    <select class="form-control" id="type" wire:model="type">
                        <option value="">Pilih tipe transaksi</option>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                    @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <!-- Deskripsi -->
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" rows="5" id="description" wire:model="description" placeholder="Masukkan deskripsi transaksi"></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
    
                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </form>
        </div>
    </div>    
</div>
