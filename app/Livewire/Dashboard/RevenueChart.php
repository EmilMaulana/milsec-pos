<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class RevenueChart extends Component
{
    public $revenues;

    // public function mount()
    // {
    //     $storeId = Auth::user()->store_id; // Mengambil toko berdasarkan user yang login

    //     // Ambil pendapatan bulan ini (dari tanggal 1 bulan ini sampai sekarang)
    //     $revenues = Transaction::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
    //         ->where('store_id', $storeId)
    //         ->whereMonth('created_at', now()->month)
    //         ->whereYear('created_at', now()->year)
    //         ->groupBy('date')
    //         ->orderBy('date')
    //         ->get();

    //     // Format data untuk chart.js
    //     $this->revenues = $revenues->map(function ($revenue) {
    //         return [
    //             'date' => $revenue->date,
    //             'total' => $revenue->total,
    //         ];
    //     });
    // }

    public function render()
    {   
        $storeId = Auth::user()->store_id;

        // Mendapatkan pendapatan dari transaksi di bulan ini
        $revenues = Transaction::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('store_id', $storeId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format data untuk Chart.js
        $formattedRevenues = $revenues->map(function ($revenue) {
            return [
                'date' => $revenue->date,
                'total' => $revenue->total,
            ];
        });

        return view('livewire.dashboard.revenue-chart', [
            'revenues' => $formattedRevenues
        ]);
    }
}

