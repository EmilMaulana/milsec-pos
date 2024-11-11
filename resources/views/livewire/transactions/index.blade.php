<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="mt-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Transaksi Kasir</h2>
                <div class="row">
                    <div class="col-md-5 mb-5">
                        <h5>Daftar Produk</h5>
                        <input type="text" class="form-control mb-2" placeholder="Cari produk..." wire:model.live="search" autofocus>
                        <div class="list-group" style="height: 500px; overflow-y: auto;">
                            <div wire:poll.5s>
                                @forelse ($products as $product)
                                    <div class="card mb-2 shadow-sm" 
                                        style="cursor: {{ $product->stock > 0 ? 'pointer' : 'not-allowed' }}; border: 1px solid #dee2e6; {{ $product->stock === 0 ? 'opacity: 0.6;' : '' }}"
                                        wire:click="{{ $product->stock > 0 ? 'addTransactionItem(' . $product->id . ')' : '' }}" 
                                        @if($product->stock === 0) 
                                            class="disabled" 
                                            aria-disabled="true" 
                                        @endif>
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" 
                                                    style="width: 40px; height: 40px; font-size: 1.2em;">
                                                    {{ substr($product->name, 0, 2) }}
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                                    <small class="text-muted">Kode: {{ $product->id }}</small>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="text-success">Rp. {{ number_format($product->sell_price, 2) }}</h5>
                                                <h6 class="text-danger text-sm">DISKON Rp. {{ number_format($product->disc, 2) }}</h6>
                                                <small class="{{ $product->stock === 0 ? 'text-danger' : 'text-muted' }}">
                                                    Stok: {{ $product->stock }} {{ $product->stock === 0 ? '(Tidak Tersedia)' : '' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="card mb-2 shadow-sm">
                                        <div class="card-body">
                                            <div class="text-center mt-3">
                                                <p class="text-muted">Produk tidak ditemukan</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <h5 class="text-primary mb-3">Item dalam Transaksi</h5>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card shadow-sm" style="border-radius: 12px;">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-1">
                                            Kasir : {{ auth()->user()->fullname }} 
                                            <span class="text-muted"> | Tanggal : <span id="realtime-clock">{{ now()->format('d-m-Y H:i:s') }}</span></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive shadow-sm" style="border-radius: 8px;">
                            <table class="table table-hover table-borderless">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Produk</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Diskon</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactionItems as $index => $item)
                                        <tr style="border-bottom: 1px solid #e9ecef;">
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $item['product']->name }}</td>
                                            <td class="text-end text-success">Rp. {{ number_format($item['product']->sell_price, 2) }}</td>
                                            <td class="text-end text-danger">Rp. {{ number_format($item['product']->disc * $item['quantity'], 2) }}</td>
                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                            <td class="text-end text-primary">Rp. {{ number_format($item['product']->sell_price * $item['quantity'], 2) }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <button class="btn btn-sm btn-warning m-1" wire:click="decreaseTransactionItemQuantity({{ $index }})"><i class="fas fa-minus"></i></button>
                                                    {{-- <button class="btn btn-sm btn-danger m-1" wire:click="removeTransactionItem({{ $index }})"><i class="fas fa-trash-alt"></i></button> --}}
                                                    <button class="btn btn-sm btn-primary m-1" wire:click="increaseTransactionItemQuantity({{ $index }})"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                                    <h5 class="text-success mb-1 d-flex align-items-center w-100 w-md-auto">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Total: Rp. <span class="fw-bold ms-1"> {{ number_format($totalPrice, 2) }}</span>
                                    </h5>
                                    <h5 class="text-success mb-0 d-flex align-items-center w-100 w-md-auto">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Total Item: <span class="fw-bold ms-1"> {{ collect($transactionItems)->sum('quantity') }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    
                        <button class="btn btn-success btn-lg w-100 mt-3" style="border-radius: 8px;" 
                                data-bs-toggle="modal" data-bs-target="#paymentModal"
                                @if($totalPrice == 0) disabled @endif>
                            <i class="fas fa-check-circle me-2"></i> BAYAR
                        </button>

                        <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetModal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="text-success text-center mb-3">
                                            Total Pembayaran: <span class="fw-bold">Rp. {{ number_format($totalPrice, 2) }}</span>
                                        </h5>

                                        <div class="mb-3">
                                            <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                                            <select id="paymentMethod" class="form-select" wire:model="paymentMethod">
                                                <option value="cash">Cash</option>
                                                <option value="qris">QRIS</option>
                                            </select>
                                            @error('paymentMethod') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="paymentAmount" class="form-label">No WhatsApp</label>
                                            <input type="text" id="phone" class="form-control" wire:model="phone" placeholder="Masukkan no whatsapp untuk mengirimkan struk">
                                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="paymentAmount" class="form-label">Nominal Uang</label>
                                            <input type="text" id="paymentAmount" class="form-control" wire:model.live="paymentAmount" value="{{ $paymentAmount }}" placeholder="Masukkan nominal uang">
                                            @error('paymentAmount') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-primary m-1 py-4" wire:click="set('paymentAmount', 50000)">Rp. 50.000</button>
                                            <button class="btn btn-primary m-1 py-4" wire:click="set('paymentAmount', 100000)">Rp. 100.000</button>
                                            <button class="btn btn-primary m-1 py-4" wire:click="set('paymentAmount', 150000)">Rp. 150.000</button>
                                            <button class="btn btn-primary m-1 py-4" wire:click="set('paymentAmount', 200000)">Rp. 200.000</button>
                                        </div>

                                        @if ($changeAmount >= 0)
                                            <div class=" alert alert-info text-center mt-3 py-5">
                                                Kembalian: <span class="h3 font-weight-bold">Rp. {{ number_format($changeAmount, 2) }}</span> 
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center mt-3 py-5">
                                                Nominal pembayaran belum cukup!
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetModal">Tutup</button>
                                        <button type="button" class="btn btn-primary" wire:click="confirmPayment" onclick="openPrintWindow()">Konfirmasi Pembayaran</button>
                                    </div>                                                                        
                                </div>
                            </div>
                        </div>
                    </div>                                                      
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
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-success py-2 px-5',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        window.print();
                    }
                });
            });
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            function openPrintWindow() {
            // Membuka tab baru
            var printWindow = window.open('', '_blank')};
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                    },
                    buttonsStyling: false,
                });
            });
        </script>
    @endif

    <script>
        function updateClock() {
            const now = new Date();
            const formattedDate = now.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(',', '');
            document.getElementById('realtime-clock').textContent = formattedDate.replace(/\//g, '-');
        }
    
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</div>