<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Tentukan properti ini jika belum ada
    // protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

}
