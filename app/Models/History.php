<?php

namespace App\Models;

use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;

    protected $primaryKey = 'history_id';

    protected $fillable = [
        'user_id',
        'borrowing_id',
        'activity',
        'description',
        // 'ip_address',
        // 'user_agent',
        'performed_by',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id', 'borrowing_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by', 'user_id');
    }
}