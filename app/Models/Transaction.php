<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            // Buat receipt_id unik jika belum ada
            if (empty($transaction->receipt_id)) {
                $transaction->receipt_id = 'REC-' . strtoupper(Str::random(8));
            }
        });
    }

    // Di model Transaction
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi one to one dengan CustomerPhone
    public function customerPhone()
    {
        return $this->hasOne(CustomerPhone::class, 'transaction_id');
    }
    
    // Fungsi untuk menghitung total keuntungan transaksi
    public function calculateProfit()
    {
        $profit = 0;

        foreach ($this->items as $item) {
            // Dapatkan harga dasar dan harga jual
            $basePrice = $item->product->base_price;
            $sellPrice = $item->price; // harga jual disimpan di transaction_items

            // Hitung keuntungan untuk item ini
            $profit += ($sellPrice - $basePrice) * $item->quantity;
        }

        return $profit;
    }
    
}
