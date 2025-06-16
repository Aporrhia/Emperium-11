<?php

namespace Database\Factories;

use App\Models\UserStats;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserStatsFactory extends Factory
{
    protected $model = UserStats::class;

    public function definition()
    {
        return [
            'user_id' => null, // Set this in your test
            'balance' => $this->faker->numberBetween(0, 10000),
            'total_expenses' => $this->faker->numberBetween(0, 10000),
            'total_income' => $this->faker->numberBetween(0, 10000),
        ];
    }
}