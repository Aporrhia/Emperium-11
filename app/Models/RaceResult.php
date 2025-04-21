<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaceResult extends Model
{
    protected $fillable = ['race_id', 'horse_id', 'position'];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function horse()
    {
        return $this->belongsTo(Horse::class);
    }
}