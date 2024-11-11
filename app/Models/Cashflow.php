<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke model User.
     * Setiap cashflow terkait dengan satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Store.
     * Setiap cashflow terkait dengan satu store.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
