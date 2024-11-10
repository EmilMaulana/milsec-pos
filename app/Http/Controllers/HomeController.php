<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $storeId = Auth::user()->store_id;

        // Mendapatkan data pendapatan per hari untuk bulan sekarang dan 3 bulan sebelumnya
        $revenues = Transaction::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('store_id', $storeId)
            ->whereBetween('created_at', [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format data untuk Chart.js
        $formattedRevenues = $revenues->map(function ($revenue) {
            return [
                'date' => $revenue->date,
                'total' => $revenue->total,
            ];
        });

        return view('home', [
            'revenues' => $formattedRevenues
        ]);
    }



}
