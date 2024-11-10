<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction as TransactionModel;
use App\Models\User;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class Transaction extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedPaymentMethod = ''; // Variabel untuk menyimpan metode pembayaran yang dipilih
    public $selectedCashier = ''; // Untuk menyimpan kasir yang dipilih
    public $searchReceipt = ''; // Untuk menyimpan ID struk yang dicari
    public $selectedDateRange = ''; // Untuk menyimpan rentang tanggal yang dipilih
    public $startDate; // Untuk menyimpan tanggal awal rentang
    public $endDate; // Untuk menyimpan tanggal akhir rentang

    public function render()
    {
        // Ambil store_id dari pengguna yang sedang login
        $store_id = Auth::user()->store->id;

        // Query dasar untuk transaksi yang terfilter
        $transactionsQuery = TransactionModel::where('store_id', $store_id)
            ->with([
                'user' => function ($query) {
                    // Sertakan pengguna yang di-soft delete
                    $query->withTrashed();
                },
                'items.product'
            ])
            ->when($this->selectedPaymentMethod, function ($query) {
                return $query->where('payment_method', $this->selectedPaymentMethod);
            })
            ->when($this->selectedCashier, function ($query) {
                return $query->whereHas('user', function ($query) {
                    return $query->withTrashed() // Tambahkan withTrashed di sini juga jika pengguna yang terhapus perlu difilter
                                ->where('email', $this->selectedCashier);
                });
            })
            ->when($this->searchReceipt, function ($query) {
                return $query->where('receipt_id', 'LIKE', '%' . $this->searchReceipt . '%');
            });


        // Terapkan filter rentang tanggal pada transaksi
        $this->applyDateRange($transactionsQuery);

        // Salin query untuk paginasi, tanpa mengubah query asli
        $paginatedTransactions = clone $transactionsQuery;

        // Lakukan paginasi pada query transaksi
        $transactions = $paginatedTransactions->orderBy('transaction_date', 'desc')->paginate(15);

        // Hitung total keuntungan, total tagihan, dan total dibayarkan dari semua transaksi yang terfilter
        $totalTagihan = $transactionsQuery->sum('total_price');
        $totalDibayarkan = $transactionsQuery->sum('payment_amount');

        // Hitung total keuntungan dari transaksi yang terfilter
        $totalKeuntungan = $transactionsQuery->get()->sum(function ($transaction) {
            return $transaction->calculateProfit();
        });

        // Mengambil email kasir yang unik dari pengguna yang memiliki toko yang sama
        $cashiers = User::where('store_id', $store_id)->withTrashed()->distinct()->pluck('email');

        return view('livewire.report.transaction', [
            'transactions' => $transactions,
            'cashiers' => $cashiers,
            'totalKeuntungan' => $totalKeuntungan,
            'totalTagihan' => $totalTagihan,
            'totalDibayarkan' => $totalDibayarkan,
        ]);
    }


    protected function applyDateRange($query)
    {
        // Filter berdasarkan rentang tanggal
        switch ($this->selectedDateRange) {
            case 'today':
                $query->whereDate('transaction_date', now()->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('transaction_date', now()->subDay()->toDateString());
                break;
            case 'last_7_days':
                $query->whereBetween('transaction_date', [now()->subDays(6), now()]);
                break;
            case 'this_month':
                $query->whereMonth('transaction_date', now()->month);
                break;
            case 'last_month':
                $query->whereMonth('transaction_date', now()->subMonth()->month)
                    ->whereYear('transaction_date', now()->subMonth()->year);
                break;
            case 'custom_range':
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
                }
                break;
        }
    }

    public function updatedSelectedDateRange($value)
    {
        if ($value !== 'custom_range') {
            $this->startDate = null; // Reset tanggal awal
            $this->endDate = null; // Reset tanggal akhir
        }
        $this->resetPage(); // Reset halaman paginasi saat filter berubah
    }

    public function downloadTransactionReport()
    {
        // Buat nama file dengan menambahkan tanggal dan waktu saat ini
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fileName = "transactions_{$timestamp}.xlsx";

        // Ambil ID toko dari pengguna yang sedang login
        $store_id = auth()->user()->store_id;

        // Kembalikan unduhan dengan filter berdasarkan store_id
        return Excel::download(new TransactionsExport($this->selectedDateRange, $this->startDate, $this->endDate, $store_id), $fileName);
    }
}
