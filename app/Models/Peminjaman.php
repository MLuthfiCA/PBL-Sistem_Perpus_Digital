<?php

namespace App\Models;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'batas_kembali',
        'tanggal_kembali',
        'status',
        'denda',
        'status_denda',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
