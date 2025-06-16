<?php

namespace Database\Factories;

use App\Models\Race;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RaceFactory extends Factory
{
    protected $model = Race::class;

    public function definition()
    {
        return [
            'status' => 'upcoming',
            'start_time' => Carbon::now()->addHour(),
            'duration' => $this->faker->numberBetween(0, 2),
            'distance' => $this->faker->numberBetween(0, 2000),
        ];
    }
}