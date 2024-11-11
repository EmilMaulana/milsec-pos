<?php

namespace App\Livewire\Cashflow;

use App\Models\Cashflow as ModelCashflow;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $store_id = Auth::user()->store->id;
        
        // Tampilkan cashflow hanya untuk store yang terkait dengan user
        $cashflows = ModelCashflow::where('store_id', $store_id)
                    ->latest()
                    ->paginate(10);

        return view('livewire.cashflow.index', [
            'cashflows' => $cashflows
        ]);
    }
}
