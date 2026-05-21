<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Peminjaman;
use Illuminate\Notifications\Notifiable;

/**
 * PHPDoc di bawah ini adalah "kunci" agar VS Code tahu $role itu ada
 * @property string $role
 * @property string $username
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'username',
        'role',
        'identity_number',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'user_id', 'user_id');
    }
}