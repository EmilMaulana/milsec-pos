<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('transactions.index', [
            'title' => 'Transaksi'
        ]);
    }

    public function print()
    {
        $transactionReceipt = session('transaction_receipt');
        
        if (!$transactionReceipt) {
            return redirect()->route('transaction.index')->with('error', 'Struk tidak tersedia.');
        }
        // Set flash message untuk redirect nanti
        session()->flash('message', 'Transaksi Berhasil.');
        return view('transactions.print', compact('transactionReceipt'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function history()
    {
        return view('transactions.history', [
            'title' => 'Riwayat Transaksi'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
