<?php

namespace Database\Factories;

use App\Models\Horse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HorseFactory extends Factory
{
    protected $model = Horse::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'speed' => $this->faker->numberBetween(50, 80),
        ];
    }
}