<?php

namespace App\Models;

use App\Models\Peminjaman;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'buku_id';
    protected $fillable = [
        'buku_id',
        'judul',
        'slug',
        'penulis',
        'genre',
        'isbn',
        'penerbit',
        'tahun_terbit',
        'cetakan',
        'bahasa',
        'kategori_id',
        'stok',
        'status',
        'deskripsi',
        'cover',
        'tampil_katalog'
    ];

    // Translate raw status to Indonesian for UI consistency
    public function getStatusAttribute($value)
    {
        $map = [
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'lost' => 'Hilang',
            'maintenance' => 'Perawatan',
        ];
        return $map[$value] ?? $value;
    }
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    // Category relationship
    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id', 'kategori_id');
    }
}