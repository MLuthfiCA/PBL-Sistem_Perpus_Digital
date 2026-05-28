<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model RIWAYAT (ERD)
 *
 * @property int $id_riwayat
 * @property int $id_pengguna
 * @property int|null $id_peminjaman
 * @property string $tanggal
 * @property string $aktivitas
 * @property string|null $deskripsi
 */
class Riwayat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'riwayat';
    protected $primaryKey = 'id_riwayat';
    public $timestamps = true;

    protected $fillable = [
        'id_pengguna',
        'id_peminjaman',
        'tanggal',
        'aktivitas',
        'deskripsi',
        'ip_address',
        'user_agent',
    ];

    // ==============================
    // RELATIONSHIPS (ERD)
    // ==============================

    /**
     * RIWAYAT N -- 1 PENGGUNA
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * RIWAYAT N -- 1 PEMINJAMAN
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}
