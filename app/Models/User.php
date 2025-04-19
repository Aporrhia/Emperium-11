<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function stats()
    {
        return $this->hasOne(UserStats::class);
    }

    public function ownedProperties()
    {
        return $this->hasMany(OwnedProperty::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->stats()->create([
                'balance' => 0,
                'total_income' => 0,
                'total_expenses' => 0,
            ]);
        });
    }
}
