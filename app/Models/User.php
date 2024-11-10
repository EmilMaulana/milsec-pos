<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
USE Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_OWNER = 'owner';
    const ROLE_STAFF = 'staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (is_null($this->last_name)) {
            return "{$this->name}";
        }

        return "{$this->name} {$this->last_name}";
    }

    /**
     * Set the user's password.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isOwner()
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isStaff()
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id'); // Ubah relasi ini
    }

    public function hasStore()
    {
        return $this->store()->exists(); // Memeriksa apakah ada store yang terkait
    }

}
