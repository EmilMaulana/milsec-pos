<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div x-data="{
        todayTransactions: 0,
        targetTransactions: {{ $todayTransactions }},
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
        animateTransactions() {
            let start = 0;
            const duration = 1000; // Durasi animasi dalam milidetik
            const step = this.targetTransactions / (duration / 16); // Hitungan per frame
    
            const interval = setInterval(() => {
                if (start >= this.targetTransactions) {
                    clearInterval(interval);
                    this.todayTransactions = this.targetTransactions;
                } else {
                    start += step;
                    this.todayTransactions = Math.floor(start);
                }
            }, 16); // 16ms untuk mencapai sekitar 60fps
        }
    }" x-init="animateTransactions()">
        <div class="card border-left-warning shadow h-100 py-3">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Transaksi Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <span x-text="formatNumber(todayTransactions)"></span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</div>
