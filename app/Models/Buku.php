<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku'; // sesuaikan dengan nama tabel kamu

    protected $fillable = [
        'book_id',
        'judul',
        'penulis',
        'genre',
        'isbn',
        'penerbit',
        'tahun_terbit',
        'cetakan',
        'bahasa',
        'kategori',
        'stok',
        'status',
        'deskripsi',
        'cover',
        'tampil_katalog'
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}