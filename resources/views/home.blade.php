@extends('layouts.admin')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Dashboard') }}</h1>

    @if (session()->has('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    confirmButtonText: ' OK ',
                    customClass: {
                        confirmButton: 'btn btn-success',
                    },
                    buttonsStyling: false,
                });
            });
        </script>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row d-flex justify-content-center">
        <div class="col-md-3 mb-4">
            @livewire('dashboard.earnings-monthly')
        </div>
        <div class="col-md-3 mb-4">
            @livewire('dashboard.daily-earnings')
        </div>
        <div class="col-md-3 mb-4">
            @livewire('dashboard.today-sales')
        </div>
        <div class="col-md-3 mb-4">
            @livewire('dashboard.today-transactions')
        </div>
    </div>
    
    

    <div class="row">
        
        <!-- Content Column -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pendapatan 4 Bulan Terakhir</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        let chart;
    
        // Array warna untuk setiap bulan
        const monthColors = [
            'rgba(255, 99, 132, 0.2)', // Januari
            'rgba(54, 162, 235, 0.2)', // Februari
            'rgba(255, 206, 86, 0.2)', // Maret
            'rgba(75, 192, 192, 0.2)', // April
            'rgba(153, 102, 255, 0.2)', // Mei
            'rgba(255, 159, 64, 0.2)', // Juni
            'rgba(255, 99, 132, 0.2)', // Juli
            'rgba(54, 162, 235, 0.2)', // Agustus
            'rgba(255, 206, 86, 0.2)', // September
            'rgba(75, 192, 192, 0.2)', // Oktober
            'rgba(153, 102, 255, 0.2)', // November
            'rgba(255, 159, 64, 0.2)'  // Desember
        ];
    
        // Inisialisasi Chart.js
        function initChart(labels, data, months) {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan Per Hari',
                        data: data,
                        backgroundColor: months.map(month => monthColors[month]),
                        borderColor: months.map(month => monthColors[month].replace('0.2', '1')),
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
        }
    
        // Fungsi untuk memperbarui chart
        function updateChart(labels, data, months) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = data;
            chart.data.datasets[0].backgroundColor = months.map(month => monthColors[month]);
            chart.data.datasets[0].borderColor = months.map(month => monthColors[month].replace('0.2', '1'));
            chart.update();
        }
    
        // Panggil data awal ketika halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const revenues = @json($revenues);
    
            // Format tanggal menjadi format yang lebih mudah dibaca
            const labels = revenues.map(revenue => new Date(revenue.date).toLocaleDateString('id-ID'));
            const data = revenues.map(revenue => parseFloat(revenue.total));
    
            // Menentukan bulan dari setiap data
            const months = revenues.map(revenue => new Date(revenue.date).getMonth()); // Mendapatkan bulan dari tanggal
    
            // Jika chart belum ada, inisialisasi
            if (!chart) {
                initChart(labels, data, months);
            } else {
                // Update chart dengan data baru
                updateChart(labels, data, months);
            }
        });
    </script>
    
        
@endsection
