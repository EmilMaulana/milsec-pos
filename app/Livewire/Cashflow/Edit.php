<?php

namespace App\Livewire\Cashflow;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Cashflow as ModelCashflow;

class Edit extends Component
{
    public $starting_balance;
    public $amount;
    public $type;
    public $ending_balance;
    public $description;

    public function mount(ModelCashflow $cashflow)
    {
        $this->starting_balance = $this->getStartingBalance();
        $this->amount = $cashflow->amount;
        $this->type = $cashflow->type;
        $this->ending_balance = $cashflow->ending_balance;
        $this->description = $cashflow->description;
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

    public function update(ModelCashflow $cashflow)
    {
        // Validasi input
        $this->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:255',
        ]);

        // Hitung ulang saldo akhir sebelum disimpan
        $this->calculateEndingBalance();

        // Simpan data awal untuk perbandingan
        $originalData = $cashflow->getAttributes(); // Data sebelum update
        $updatedData = [
            'starting_balance' => $this->starting_balance,
            'amount' => $this->amount,
            'type' => $this->type,
            'ending_balance' => $this->ending_balance,
            'description' => $this->description,
        ];

        // Tentukan atribut yang berubah
        $changes = [];
        foreach ($updatedData as $key => $value) {
            if (isset($originalData[$key]) && $originalData[$key] !== $value) {
                $changes[$key] = [
                    'before' => $originalData[$key],
                    'after' => $value,
                ];
            }
        }

        // Update data cashflow
        $cashflow->update($updatedData);

        // Log aktivitas untuk mencatat pembaruan jika ada perubahan
        if (!empty($changes)) {
            $changeDescriptions = [];
            foreach ($changes as $field => $change) {
                $changeDescriptions[] = "Field '$field' diubah dari '{$change['before']}' menjadi '{$change['after']}'";
            }
            $changeLog = implode(', ', $changeDescriptions);
            logActivity('User memperbarui data cashflow: ' . $this->type . ' sebesar ' . $this->amount . '. Perubahan: ' . $changeLog);
        }

        // Update saldo awal setelah transaksi
        $this->starting_balance = $this->ending_balance;

        session()->flash('message', 'Data cashflow berhasil diperbarui!');

        // Redirect ke halaman indeks cashflow untuk melihat data yang sudah diupdate
        return redirect()->route('cashflow.index');
    }



    public function render()
    {
        return view('livewire.cashflow.edit');
    }
}
