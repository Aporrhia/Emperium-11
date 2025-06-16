<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Race extends Model
{
    use HasFactory;
    protected $fillable = ['start_time', 'status', 'distance', 'duration'];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function horses()
    {
        return $this->belongsToMany(Horse::class, 'race_horse')->withTimestamps();
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function raceResults()
    {
        return $this->hasMany(RaceResult::class);
    }

    public function getTotalBidSumAttribute()
    {
        return $this->bids()->sum('amount') ?? 0.0;
    }
}