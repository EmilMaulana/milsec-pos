<div>
    {{-- The best athlete wants his opponent at his best. --}}
        <h1 class="h3 mb-2 text-gray-800">Daftar Transaksi</h1>
        <p class="mb-4">Informasi detail transaksi yang dilakukan oleh kasir.</p>
    
        <div class="card shadow mb-4">
            <div class="card-header ">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">Transaksi</h5>
                    <div class="d-flex align-items-center">
                        <!-- Tombol Download Laporan -->
                        <button class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Laporan yang diekspor akan mencakup data transaksi sesuai dengan rentang tanggal yang Anda pilih.">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button wire:click="downloadTransactionReport" class="btn btn-success me-2">
                            <i class="fas fa-download"></i> Download Laporan
                        </button>
                        
                    </div>
                </div>   
            </div>
            <div class="card-body">
                <!-- Dropdown untuk memilih metode pembayaran -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="paymentMethodFilter" class="form-label">Metode Pembayaran</label>
                            <select id="paymentMethodFilter" class="form-select" wire:model.live="selectedPaymentMethod">
                                <option value="">Semua</option>
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <!-- Tambahkan opsi lain sesuai kebutuhan -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="cashierFilter" class="form-label">Kasir</label>
                            <select id="cashierFilter" class="form-select" wire:model.live="selectedCashier">
                                <option value="">Semua</option>
                                @foreach ($cashiers as $cashier)
                                    <option value="{{ $cashier }}">{{ $cashier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @if($selectedDateRange === 'custom_range')
                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                                    <input type="date" id="startDate" class="form-control" wire:model.live="startDate">
                                </div>
                                <div class="mb-3">
                                    <label for="endDate" class="form-label">Tanggal Akhir</label>
                                    <input type="date" id="endDate" class="form-control" wire:model.live="endDate">
                                </div>
                            @endif
                        <div class="mb-3">
                            <label for="dateRangeFilter" class="form-label">Rentang Tanggal</label>
                            <select id="dateRangeFilter" class="form-select" wire:model.live="selectedDateRange">
                                <option value="">Semua</option>
                                <option value="today">Hari Ini</option>
                                <option value="yesterday">Kemarin</option>
                                <option value="last_7_days">7 Hari Terakhir</option>
                                <option value="this_month">Bulan Ini</option>
                                <option value="last_month">Bulan Lalu</option>
                                <option value="custom_range">Custom Range</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Cari ID Struk</label>
                            <input type="text" class="form-control" wire:model.live="searchReceipt" placeholder="Cari ID Struk">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-nowrap text-center" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Struk</th>
                                <th>Email Kasir</th>
                                <th>Tanggal</th>
                                <th>Metode Pembayaran</th>
                                <th>Keuntungan</th>
                                <th>Total Tagihan</th>
                                <th>Jumlah Dibayarkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($transactions->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data transaksi yang tersedia.</td>
                                </tr>
                            @else
                                @foreach ($transactions as $index => $transaction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaction->receipt_id }}</td>
                                        <td>{{ $transaction->user->email ?? 'Unknown' }}</td>
                                        <td>{{ $transaction->transaction_date }}</td>
                                        <td>{{ ucfirst($transaction->payment_method) }}</td>
                                        <td>Rp. {{ number_format($transaction->calculateProfit(), 2) }}</td>
                                        <td>Rp. {{ number_format($transaction->total_price, 2) }}</td>
                                        <td>Rp. {{ number_format($transaction->payment_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td colspan="5" class="text-end">Total</td>
                                    <td>Rp. {{ number_format($totalKeuntungan, 2) }}</td>
                                    <td>Rp. {{ number_format($totalTagihan, 2) }}</td>
                                    <td>Rp. {{ number_format($totalDibayarkan, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>                        
                    </table>
                </div>
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>            
        </div>
</div>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>