<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div x-data="{
        todayEarnings: 0,
        targetEarnings: {{ $todayEarnings }},
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
        animateEarnings() {
            let start = 0;
            const duration = 1000; // Durasi animasi dalam milidetik
            const step = this.targetEarnings / (duration / 16); // Hitungan per frame
    
            const interval = setInterval(() => {
                if (start >= this.targetEarnings) {
                    clearInterval(interval);
                    this.todayEarnings = this.targetEarnings;
                } else {
                    start += step;
                    this.todayEarnings = Math.floor(start);
                }
            }, 16); // 16ms untuk mencapai sekitar 60fps
        }
    }" x-init="animateEarnings()">
        <div class="card border-left-success shadow h-100 py-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">pendapatan hari ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <span x-text="formatNumber(todayEarnings)"></span></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
