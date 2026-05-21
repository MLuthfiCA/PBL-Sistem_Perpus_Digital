<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowingDetail extends Model
{
    use HasFactory;

    protected $table = 'borrowing_details';
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
        return $this->belongsTo(Borrowing::class, 'borrowing_id', 'borrowing_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}