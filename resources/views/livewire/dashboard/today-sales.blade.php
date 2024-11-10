<div>
    {{-- Success is as dangerous as failure. --}}
    <div x-data="{
        todaySales: 0,
        targetSales: {{ $todaySales }},
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
        animateSales() {
            let start = 0;
            const duration = 1000; // Durasi animasi dalam milidetik
            const step = this.targetSales / (duration / 16); // Hitungan per frame
    
            const interval = setInterval(() => {
                if (start >= this.targetSales) {
                    clearInterval(interval);
                    this.todaySales = this.targetSales;
                } else {
                    start += step;
                    this.todaySales = Math.floor(start);
                }
            }, 16); // 16ms untuk mencapai sekitar 60fps
        }
    }" x-init="animateSales()">
        <div class="card border-left-info shadow h-100 py-3">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Produk Terjual Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <span x-text="formatNumber(todaySales)"></span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
