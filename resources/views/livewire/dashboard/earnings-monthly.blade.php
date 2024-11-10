<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div x-data="{
            monthlyEarnings: 0,
            targetEarnings: {{ $monthlyEarnings }},
            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            },
            animateEarnings() {
                let start = 0;
                const duration = 1000; // durasi animasi dalam milidetik
                const step = this.targetEarnings / (duration / 16); // hitungan per frame

                const interval = setInterval(() => {
                    if (start >= this.targetEarnings) {
                        clearInterval(interval);
                        this.monthlyEarnings = this.targetEarnings;
                    } else {
                        start += step;
                        this.monthlyEarnings = Math.floor(start);
                    }
                }, 16); // 16ms untuk mencapai sekitar 60fps
            }
        }" x-init="animateEarnings()">
        
        <div class="card border-left-primary shadow h-100 py-3">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendapatan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <span x-text="formatNumber(monthlyEarnings)"></span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
