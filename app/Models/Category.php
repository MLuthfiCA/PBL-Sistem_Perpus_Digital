<?php

namespace App\Models;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    public $timestamps = true;

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id', 'kategori_id');
    }
}