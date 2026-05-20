<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowingDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'borrowing_detail_id';

    protected $fillable = [
        'borrowing_id',
        'book_id',
        'quantity',
        'due_date',
        'return_condition',
        'fine_per_item',
        'returned_at',
        'notes',
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