<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class House extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'price',
        'type',
        'images',
        'latitude', 
        'longitude'
    ];

    public function ownerships(): MorphMany
    {
        return $this->morphMany(OwnedProperty::class, 'ownable');
    }
}