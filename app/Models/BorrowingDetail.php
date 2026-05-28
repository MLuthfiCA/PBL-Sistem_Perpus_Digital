<?php

namespace App\Models;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowingDetail extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'detail_peminjaman_id';

    protected $fillable = [
        'peminjaman_id',
        'buku_id',
        'jumlah',
        'batas_kembali_buku',
        'kondisi_kembali',
        'denda_per_item',
        'dikembalikan_pada',
        'catatan',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function borrowing()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }

    public function book()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'buku_id');
    }
}