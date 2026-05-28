<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model KATEGORI (ERD)
 *
 * @property int $id_kategori
 * @property string $nama_kategori
 * @property string|null $deskripsi
 * @property string|null $slug
 */
class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = true;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'slug',
    ];

    // ==============================
    // RELATIONSHIPS (ERD)
    // ==============================

    /**
     * KATEGORI 1 -- N BUKU
     */
    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_kategori', 'id_kategori');
    }
}
