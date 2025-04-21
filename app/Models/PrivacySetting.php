<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacySetting extends Model
{
    protected $fillable = [
        'user_id',
        'show_in_tier_list',
        'hide_personal_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}