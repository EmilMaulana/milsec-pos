<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\WablasService;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResendTransactionMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $usersInStore;

    /**
     * Create a new job instance.
     *
     * @param Transaction $transaction
     * @param array $usersInStore
     * @return void
     */
    public function __construct(Transaction $transaction, array $usersInStore)
    {
        $this->transaction = $transaction;
        $this->usersInStore = $usersInStore;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Menggunakan WablasService untuk mengirim pesan
        $wablasService = new WablasService();
        
        // Format pesan transaksi
        $message = $this->formatTransactionMessage();

        // Kirim pesan ke setiap pengguna di toko yang sama
        foreach ($this->usersInStore as $user) {
            $phoneNumber = $this->normalizePhoneNumber($user->phone);

            // Cek apakah nomor telepon valid sebelum mengirim pesan
            if ($this->isValidPhoneNumber($phoneNumber)) {
                try {
                    $wablasService->resendMessage($phoneNumber, $message);
                    Log::info("Pesan berhasil dikirim ke: $phoneNumber");
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim pesan ke $phoneNumber: " . $e->getMessage());
                }
            } else {
                Log::warning("Nomor telepon tidak valid: $phoneNumber");
            }
        }

        // Kirim pesan ke customer jika tersedia
        if ($this->transaction->phone) {
            $customerPhoneNumber = $this->normalizePhoneNumber($this->transaction->phone);

            // Cek apakah nomor telepon customer valid sebelum mengirim pesan
            if ($this->isValidPhoneNumber($customerPhoneNumber)) {
                try {
                    $wablasService->resendMessage($customerPhoneNumber, $message);
                    Log::info("Pesan berhasil dikirim ke customer: $customerPhoneNumber");
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim pesan ke customer $customerPhoneNumber: " . $e->getMessage());
                }
            } else {
                Log::warning("Nomor telepon customer tidak valid: $customerPhoneNumber");
            }
        }
    }

    /**
     * Format pesan transaksi
     *
     * @return string
     */
    private function formatTransactionMessage()
    {
        $message = 
            "📢 *" . strtoupper($this->transaction->store->name) . "*\n\n" .
            "✅ *TRANSAKSI BERHASIL!*\n" .
            "⏰ *Waktu :* {$this->transaction->transaction_date}\n" .
            "👤 *Kasir :* {$this->transaction->user->fullname}\n" .
            "🆔 *ID Transaksi :* {$this->transaction->receipt_id}\n\n" .
            "💵 *Total :* Rp. " . number_format($this->transaction->total_price, 2) . "\n" .
            "💳 *Metode :* " . strtoupper($this->transaction->payment_method) . "\n" .
            "💰 *Jumlah :* Rp. " . number_format($this->transaction->payment_amount, 2) . "\n" .
            "💵 *Kembalian :* Rp. " . number_format($this->transaction->change_amount, 2) . "\n\n" .
            "📦 *Detail Item :*\n";

        foreach ($this->transaction->items as $item) {
            $message .= "- {$item->product->name}: {$item->quantity} pcs\n";
        }

        $message .= "\n\n_*note: pesan ini dikirim otomatis oleh sistem kami_";

        return $message;
    }

    /**
     * Normalisasi nomor telepon
     *
     * @param string $phoneNumber
     * @return string
     */
    private function normalizePhoneNumber($phoneNumber)
    {
        if (substr($phoneNumber, 0, 1) === '0 ') {
            return '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 3) === '+62') {
            return substr($phoneNumber, 1);
        }
        return $phoneNumber;
    }

    /**
     * Validasi nomor telepon
     *
     * @param string $phoneNumber
     * @return bool
     */
    private function isValidPhoneNumber($phoneNumber)
    {
        // Cek apakah nomor telepon hanya terdiri dari angka dan memiliki panjang yang sesuai
        return preg_match('/^\d{10,15}$/', $phoneNumber);
    }
}