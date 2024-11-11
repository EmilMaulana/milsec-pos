<div>
    {{-- Success is as dangerous as failure. --}}
    <h1 class="h3 mb-4 text-gray-800">Riwayat Transaksi</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
        </div>
        <div class="card-body">
            @foreach($transactions as $transaction)
                <div class="card mb-4 shadow-lg rounded-lg border-0">
                    <div class="card-body">
                        <!-- Card Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title text-primary font-weight-bold">No. Transaksi: {{ $transaction->receipt_id }}</h5>
                            <span class="badge badge-success py-3 px-3">Status: Berhasil</span>
                        </div>
                        
                        <!-- Transaction Info -->
                        <div class="mb-3">
                            <p class="card-text"><strong>Tanggal:</strong> {{ $transaction->created_at->format('d-m-Y H:i') }}</p>
                            <p class="card-text"><strong>Jumlah Total:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                            <p class="card-text"><strong>No WA:</strong> {{ $transaction->phone ? $transaction->phone : 'Tidak ada nomor' }}</p>
                        </div>
                
                        <!-- Detail Barang Section -->
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-secondary">Detail Barang:</h6>
                            <ul class="list-unstyled">
                                @foreach($transaction->items as $item)
                                    <li class="mb-3 border-bottom pb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-dark">{{ $item->product->name }}</strong>
                                            <span class="badge badge-info">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <span>Jumlah: {{ $item->quantity }} pcs</span>
                                            <span>Harga Satuan: Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
