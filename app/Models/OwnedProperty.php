<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnedProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'ownable_id', 
        'ownable_type'
    ];

    /**
     * Get the user that owns this property.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the property (Apartment or House) that is owned.
     */
    public function ownable()
    {
        return $this->morphTo();
    }
}