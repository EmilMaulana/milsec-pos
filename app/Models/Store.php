<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    // Relasi ke User
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
