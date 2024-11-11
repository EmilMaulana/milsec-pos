<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    public function index()
    {
        return view('cashflow.index', [
            'title' => 'Arus Kas'
        ]);
    }

    public function create()
    {
        return view('cashflow.create', [
            'title' => 'Tambah Data Arus Kas'
        ]);
    }

    public function edit(Cashflow $cashflow)
    {
        return view('cashflow.edit', [
            'title' => 'Edit ' . $cashflow->description,
            'cashflow' => $cashflow
        ]);
    }
}
