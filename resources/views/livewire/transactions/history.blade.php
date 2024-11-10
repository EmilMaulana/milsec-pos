<div>
    {{-- Success is as dangerous as failure. --}}
        <h1 class="h3 mb-4 text-gray-800">Riwayat Transaksi</h1>
    
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
            </div>
            <div class="card-body">
                @foreach($transactions as $transaction)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">No. Transaksi: {{ $transaction->receipt_id }}</h5>
                        <p class="card-text"><strong>Tanggal:</strong> {{ $transaction->created_at->format('d-m-Y H:i') }}</p>
                        <p class="card-text"><strong>Jumlah:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                        <p class="card-text"><strong>No WA:</strong> {{ $transaction->customer_phones ? $transaction->customer_phones->phone : 'Tidak ada nomor' }}</p>
                        
                        <!-- Tombol Kirim Ulang Struk ke WhatsApp -->
                        <button class="btn btn-primary" onclick="resendReceipt('{{ $transaction->id }}')">Kirim Ulang Struk ke WhatsApp</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    
    <script>
        function resendReceipt(transactionId) {
            // Logika untuk mengirim ulang struk ke WhatsApp
            // Misalnya, panggil fungsi di backend menggunakan AJAX atau Livewire
            alert('Struk akan dikirim ulang untuk transaksi ID: ' + transactionId);
        }
    </script>
    
    
</div>
