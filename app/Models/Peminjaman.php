<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model PEMINJAMAN (ERD)
 *
 * @property int $id_peminjaman
 * @property int $id_pengguna
 * @property string $tanggal_pinjam
 * @property string|null $tanggal_kembali
 * @property string $status
 * @property float $denda
 */
class Peminjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $timestamps = true;

    protected $fillable = [
        'id_pengguna',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'denda',
        'kode_peminjaman',
        'batas_kembali',
        'status_denda',
        'catatan',
        'id_buku',
    ];

    // ==============================
    // ACCESSORS (Compatibility)
    // ==============================

    public function getPeminjamanIdAttribute()
    {
        return $this->id_peminjaman;
    }

    public function getIdAttribute()
    {
        return $this->id_peminjaman;
    }

    public function getUserIdAttribute()
    {
        return $this->id_pengguna;
    }

    public function getBukuIdAttribute()
    {
        return $this->id_buku;
    }

    // ==============================
    // RELATIONSHIPS (ERD)
    // ==============================

    /**
     * PEMINJAMAN N -- 1 PENGGUNA
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * PEMINJAMAN 1 -- N DETAIL_PEMINJAMAN
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * PEMINJAMAN 1 -- N RIWAYAT
     */
    public function riwayat()
    {
        return $this->hasMany(Riwayat::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Relasi langsung ke buku (dipertahankan dari database lama)
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}
