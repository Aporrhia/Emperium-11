<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horse extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'speed'];

    public function races()
    {
        return $this->belongsToMany(Race::class, 'race_horse')->withTimestamps();
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function raceResults()
    {
        return $this->hasMany(RaceResult::class);
    }

    public function getWinningRateAttribute()
    {
        $totalRaces = $this->raceResults()->count();
        if ($totalRaces === 0) {
            return 0.0;
        }

        $wins = $this->raceResults()->where('position', 1)->count();
        return round(($wins / $totalRaces) * 100, 1);
    }

}