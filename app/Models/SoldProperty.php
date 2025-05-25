<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SoldProperty extends Model
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
        'address',
        'size',
        'price',
        'sale_method',
        'seller_type',
        'is_active',
        'price',
        'type',
        'images',
        'latitude', 
        'longitude'
    ];

    protected $casts = [
        'images' => 'array', 
    ];

    public function ownerships(): MorphMany
    {
        return $this->morphMany(OwnedProperty::class, 'ownable');
    }
}