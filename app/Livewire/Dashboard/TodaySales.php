<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\TransactionItem;

class TodaySales extends Component
{
    public $todaySales;

    public function mount()
    {
        $this->todaySales = $this->calculateTodaySales();
    }

    public function calculateTodaySales()
    {
        return TransactionItem::whereHas('transaction', function ($query) {
                            $query->where('store_id', auth()->user()->store_id)
                                ->whereDate('created_at', now()->toDateString());
                        })
                        ->sum('quantity');
    }


    public function render()
    {
        return view('livewire.dashboard.today-sales');
    }
}
