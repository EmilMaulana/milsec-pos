<?php

namespace App\Livewire\Transactions;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class History extends Component
{
    public $transactions; // Menyimpan transaksi untuk ditampilkan di view

    public function mount()
    {
        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Ambil transaksi yang terkait dengan store_id
        $this->transactions = Transaction::where('store_id', $store_id)->latest()->get();
    }

    public function render()
    {
        return view('livewire.transactions.history');
    }
}
