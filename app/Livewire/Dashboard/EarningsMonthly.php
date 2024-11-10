<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Transaction;

class EarningsMonthly extends Component
{
    public $monthlyEarnings;

    public function mount()
    {
        $this->monthlyEarnings = $this->calculateMonthlyEarnings();
    }

    public function calculateMonthlyEarnings()
    {
        $userStoreId = auth()->user()->store_id; // Ambil store_id dari user yang login

        return Transaction::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->where('store_id', $userStoreId) // Filter berdasarkan store_id
                        ->sum('total_price');
    }


    public function render()
    {
        return view('livewire.dashboard.earnings-monthly');
    }
}
