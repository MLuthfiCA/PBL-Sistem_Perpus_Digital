<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model PENGGUNA (ERD)
 *
 * @property int $id_pengguna
 * @property string $nama
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $identity_number
 * @property string $status
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'identity_number',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Accessor: agar $user->name tetap bisa dipakai
     */
    public function getNameAttribute(): string
    {
        return $this->nama;
    }

    public function getFullNameAttribute(): string
    {
        return $this->nama;
    }

    public function getUserIdAttribute()
    {
        return $this->id_pengguna;
    }

    public function getIdAttribute()
    {
        return $this->id_pengguna;
    }

    // ==============================
    // RELATIONSHIPS (ERD)
    // ==============================

    /**
     * PENGGUNA 1 -- N PEMINJAMAN
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * PENGGUNA 1 -- N RIWAYAT
     */
    public function riwayat()
    {
        return $this->hasMany(Riwayat::class, 'id_pengguna', 'id_pengguna');
    }
}