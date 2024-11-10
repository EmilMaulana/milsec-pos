<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Transaction;

class DailyEarnings extends Component
{
    public $todayEarnings;

    public function mount()
    {
        $this->todayEarnings = $this->calculateTodayEarnings();
    }

    public function calculateTodayEarnings()
    {
        $user = auth()->user();

        // Pastikan user memiliki toko
        if ($user && $user->store) {
            return Transaction::where('store_id', $user->store->id)
                            ->whereDate('created_at', now()->toDateString())
                            ->sum('total_price');
        }

        return 0; // Jika tidak ada toko, kembalikan 0
    }

    public function render()
    {
        return view('livewire.dashboard.daily-earnings');
    }
}
