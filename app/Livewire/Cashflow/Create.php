<?php

namespace App\Livewire\Cashflow;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Cashflow as ModelCashflow;

class Create extends Component
{
    public $starting_balance;
    public $amount;
    public $type;
    public $ending_balance;
    public $description;

    protected $rules = [
        'amount' => 'required|numeric',
        'type' => 'required|in:income,expense',
        'ending_balance' => 'required|numeric',
        'description' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->starting_balance = $this->getStartingBalance();

    }

    // Mendapatkan saldo awal berdasarkan kondisi
    public function getStartingBalance()
    {
        // Cek transaksi terakhir pada cashflow user di toko ini
        $lastTransaction = ModelCashflow::where('user_id', Auth::id())
            ->where('store_id', Auth::user()->store_id)
            ->latest()
            ->first();

        // Hitung pendapatan bulan ini
        $currentMonthIncome = $this->calculateMonthlyIncome();

        // Jika ada transaksi sebelumnya
        if ($lastTransaction) {
            // Jika ada pengeluaran (misalnya, jika saldo akhir sebelumnya lebih kecil dari 0 atau ada transaksi pengeluaran)
            if ($lastTransaction->ending_balance < 0 || $this->hasExpenses()) {
                // Ambil saldo akhir transaksi terakhir sebagai saldo awal
                return $lastTransaction->ending_balance;
            }

            // Jika tidak ada pengeluaran, ambil pendapatan bulan ini sebagai saldo awal
            return $currentMonthIncome;
        }

        // Jika belum ada transaksi sebelumnya, kembalikan pendapatan bulan ini
        return $currentMonthIncome;
    }

    // Contoh fungsi untuk mengecek apakah ada pengeluaran
    private function hasExpenses()
    {
        // Logika untuk mengecek apakah ada pengeluaran dalam bulan ini
        // Misalnya, Anda bisa memeriksa tabel transaksi untuk melihat apakah ada pengeluaran
        return ModelCashflow::where('user_id', Auth::id())
            ->where('store_id', Auth::user()->store_id)
            ->where('type', 'expense') // Misalkan 'expense' adalah tipe transaksi untuk pengeluaran
            ->whereMonth('created_at', now()->month) // Cek hanya untuk bulan ini
            ->exists();
    }


    // Fungsi menghitung pendapatan bulanan
    public function calculateMonthlyIncome()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Hitung total pendapatan bulan ini dari tabel transaksi (sesuaikan model dan kolomnya)
        $totalIncome = Transaction::where('store_id', Auth::user()->store_id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price'); // Sesuaikan dengan kolom yang menyimpan amount pendapatan

        // return number_format($totalIncome, 0, ',', '.');
        return $totalIncome;
    }

    public function calculateEndingBalance()
    {
        if ($this->type === 'expense') {
            $this->ending_balance = $this->starting_balance - $this->amount;
        } else {
            $this->ending_balance = $this->starting_balance + $this->amount;
        }
    }

    // Menyimpan data cashflow ke database
    public function store()
    {
        // Validasi input
        $this->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'description' => 'required|string',
        ]);

        // Hitung ulang saldo akhir sebelum disimpan
        $this->calculateEndingBalance();

        // Buat cashflow baru
        ModelCashflow::create([
            'user_id' => Auth::id(),
            'store_id' => Auth::user()->store_id,
            'starting_balance' => $this->starting_balance,
            'amount' => $this->amount,
            'type' => $this->type,
            'ending_balance' => $this->ending_balance,
            'description' => $this->description,
        ]);

        // Log aktivitas setelah data berhasil disimpan
        logActivity('User menambahkan data cashflow: ' . $this->type . ' sebesar ' . $this->amount);

        // Reset form setelah simpan
        $this->reset(['amount', 'type', 'description']);
        $this->starting_balance = $this->ending_balance; // Update saldo awal setelah transaksi
        session()->flash('message', 'Data cashflow berhasil ditambahkan!');
        // Redirect to the same page to display SweetAlert
        return redirect()->route('cashflow.index');
    }

    public function render()
    {
        return view('livewire.cashflow.create');
    }
}
