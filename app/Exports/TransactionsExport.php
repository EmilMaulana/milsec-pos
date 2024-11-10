<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private $index = 0; // Untuk urutan index
    private $dateRange;
    private $startDate;
    private $endDate;
    protected $store_id;

    // Konstruktor yang menerima parameter rentang tanggal
    public function __construct($dateRange = null, $startDate = null, $endDate = null, $store_id)
    {
        $this->dateRange = $dateRange;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->store_id = $store_id;
    }

    /**
     * Mengambil data transaksi berdasarkan filter tanggal dan store_id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Buat query transaksi dengan relasi user
        $query = Transaction::with('user')->where('store_id', $this->store_id);

        // Terapkan filter tanggal berdasarkan rentang yang dipilih
        if ($this->dateRange) {
            switch ($this->dateRange) {
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

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Receipt ID',
            'Kasir',
            'Tanggal Transaksi',
            'Metode Pembayaran',
            'Keuntungan',
            'Total Tagihan',
            'Jumlah Dibayar',
        ];
    }

    /**
     * Memetakan data transaksi untuk setiap baris.
     *
     * @param Transaction $transaction
     * @return array
     */
    public function map($transaction): array
    {
        $this->index++; // Tambah index setiap kali map dipanggil

        return [
            $this->index, // Menggunakan urutan index untuk kolom "No"
            $transaction->receipt_id,
            $transaction->user->fullname ?? 'Unknown',
            $transaction->transaction_date,
            ucfirst($transaction->payment_method),
            'Rp. ' . number_format($transaction->calculateProfit(), 2),
            'Rp. ' . number_format($transaction->total_price, 2),
            'Rp. ' . number_format($transaction->payment_amount, 2),
        ];
    }
}
