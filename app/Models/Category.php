<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'description',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function books()
    {
        return $this->hasMany(Book::class, 'category_id', 'category_id');
    }
}