<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model BUKU (ERD)
 *
 * @property int $id_buku
 * @property string $judul
 * @property string $penulis
 * @property string $penerbit
 * @property int $tahun_terbit
 * @property string $isbn
 * @property int $stok
 * @property string|null $cetakan
 * @property string|null $genre
 * @property string $bahasa
 */
class Buku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    public $timestamps = true;

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'stok',
        'deskripsi',
        'cetakan',
        'genre',
        'bahasa',
        'slug',
        'cover',
        'lokasi_rak',
        'tampil_katalog',
        'status',
    ];

    // ==============================
    // ACCESSORS (Compatibility)
    // ==============================

    public function getBukuIdAttribute()
    {
        return $this->id_buku;
    }

    public function getIdAttribute()
    {
        return $this->id_buku;
    }

    // ==============================

    /**
     * BUKU referenced by DETAIL_PEMINJAMAN
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_buku', 'id_buku');
    }

    /**
     * BUKU referenced by PEMINJAMAN (relasi langsung, dipertahankan)
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku', 'id_buku');
    }
}