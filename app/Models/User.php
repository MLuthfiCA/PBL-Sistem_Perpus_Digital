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

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}