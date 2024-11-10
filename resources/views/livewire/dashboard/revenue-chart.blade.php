<div>
    {{-- Stop trying to control. --}}
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pendapatan Bulan Ini</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <script>
            // Mendapatkan data dari PHP ke JavaScript
            const revenues = @json($revenues);
    
            // Map data ke format yang dapat digunakan oleh Chart.js
            const labels = revenues.map(revenue => new Date(revenue.date).toLocaleDateString('id-ID'));
            const data = revenues.map(revenue => parseFloat(revenue.total));
            // Inisialisasi Chart.js dengan data yang sudah diformat
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
</div>