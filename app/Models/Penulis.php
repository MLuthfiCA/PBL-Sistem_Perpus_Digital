<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    use HasFactory;

    protected $table = 'penulis';
    protected $primaryKey = 'id_penulis';

    protected $fillable = [
        'nama_penulis',
    ];

    /**
     * PENULIS has many BUKU (Many-to-Many)
     */
    public function buku()
    {
        return $this->belongsToMany(Buku::class, 'buku_penulis', 'id_penulis', 'id_buku');
    }
}
