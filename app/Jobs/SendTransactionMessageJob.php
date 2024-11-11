<?php

namespace App\Jobs;

use App\Services\WablasService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTransactionMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $usersInStore;
    protected $customerPhone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction, $usersInStore)
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
        $wablasService = new WablasService();
        
        // Format pesan transaksi
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

        // Kirim pesan ke setiap pengguna di toko yang sama
        foreach ($this->usersInStore as $user) {
            $phoneNumber = $user->phone;
            if (substr($phoneNumber, 0, 1) === '0') {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } elseif (substr($phoneNumber, 0, 3) === '+62') {
                $phoneNumber = substr($phoneNumber, 1);
            }
            $wablasService->sendMessage($phoneNumber, $message);
        }

        // Kirim pesan ke customer
        $wablasService->sendMessage($this->transaction->phone, $message);
    }
}
