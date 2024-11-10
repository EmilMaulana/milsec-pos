<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPhone extends Model
{
    use HasFactory;

    protected $table = 'customer_phones';
    protected $guarded =['id'];

    // Definisikan relasi ke model Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'phone_id'); // Sesuaikan dengan nama kolom foreign key di tabel transaksi
    }
}
