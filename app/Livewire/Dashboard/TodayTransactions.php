<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class TodayTransactions extends Component
{
    public $todayTransactions; // Variabel untuk menyimpan jumlah transaksi hari ini

    public function mount()
    {
        // Mengambil store_id dari user yang sedang login
        $storeId = Auth::user()->store_id; // Pastikan user memiliki relasi dengan toko

        // Mengambil jumlah transaksi hari ini berdasarkan store_id
        $this->todayTransactions = Transaction::whereDate('created_at', today())
            ->where('store_id', $storeId) // Filter berdasarkan store_id
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard.today-transactions');
    }
}
