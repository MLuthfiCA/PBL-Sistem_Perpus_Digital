<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model DETAIL_PEMINJAMAN (ERD)
 *
 * @property int $id_detail
 * @property int $id_peminjaman
 * @property int $id_buku
 * @property int $jumlah
 */
class DetailPeminjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id_detail';
    public $timestamps = true;

    protected $fillable = [
        'id_peminjaman',
        'id_buku',
        'jumlah',
        'batas_kembali_buku',
        'kondisi_kembali',
        'denda_per_item',
        'dikembalikan_pada',
        'catatan',
    ];

    // ==============================
    // RELATIONSHIPS (ERD)
    // ==============================

    /**
     * DETAIL_PEMINJAMAN N -- 1 PEMINJAMAN
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * DETAIL_PEMINJAMAN N -- 1 BUKU
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}
