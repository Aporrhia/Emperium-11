<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function stats(): HasOne
    {
        return $this->hasOne(UserStats::class);
    }

    public function ownedProperties(): HasMany
    {
        return $this->hasMany(OwnedProperty::class);
    }
    
    public function privacySetting()
    {
        return $this->hasOne(PrivacySetting::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->stats()->create([
                'balance' => 1000000,
                'total_income' => 0,
                'total_expenses' => 0,
            ]);

            $user->privacySetting()->create([
                'show_in_tier_list' => true,
                'hide_personal_info' => false,
            ]);
        });
    }
}
