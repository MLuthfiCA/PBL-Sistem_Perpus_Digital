<?php

namespace App\Models;

use App\Models\BorrowingDetail;
use App\Models\History;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowing extends Model
{
    use HasFactory;

    protected $primaryKey = 'borrowing_id';

    protected $fillable = [
        'borrowing_code',
        'user_id',
        'borrowing_date',
        'return_limit',
        'return_date',
        'status',
        'fine',
        'fine_status',
        'notes',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function details()
    {
        return $this->hasMany(BorrowingDetail::class, 'borrowing_id', 'borrowing_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'borrowing_id', 'borrowing_id');
    }
}