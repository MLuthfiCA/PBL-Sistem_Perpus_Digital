<?php

namespace App\Models;

use App\Models\BorrowingDetail;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id';

    protected $fillable = [
        'title',
        'author',
        'genre',
        'isbn',
        'publisher',
        'publication_year',
        'category_id',
        'language',
        'description',
        'status',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class, 'book_id', 'book_id');
    }
}