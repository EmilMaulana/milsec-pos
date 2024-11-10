<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction()
    {
        return view('report.transaction',[
            'title' => 'Laporan Transaksi'
        ]);
    }

    public function modal()
    {
        return view('report.modal',[
            'title' => 'Laporan Modal'
        ]);
    }
}
