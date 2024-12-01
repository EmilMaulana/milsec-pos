<?php

namespace App\Livewire\Transactions;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ResendTransactionMessageJob;
use App\Models\Transaction;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $transaction = Transaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(5);

        return view('livewire.transactions.history', [
            'transactions' => $transaction
        ]);
    }

}
