<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'balance', 
        'total_income', 
        'total_expenses'
    ];

    /**
     * Get the user that owns these stats.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}