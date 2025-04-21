<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Race;
use App\Models\Horse;
use Carbon\Carbon;

class RaceSeeder extends Seeder
{
    public function run()
    {
        $horses = Horse::all();

        $races = [
            [
                'start_time' => Carbon::now()->addMinutes(5),
                'status' => 'upcoming',
                'distance' => 1000,
                'duration' => 1,
            ]
        ];

        foreach ($races as $raceData) {
            $race = Race::create($raceData);
            $race->horses()->attach($horses->pluck('id'));
        }
    }
}