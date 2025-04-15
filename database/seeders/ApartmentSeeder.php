<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    public function run()
    {
        Apartment::create([
            'title' => 'Luxury Downtown Apartment',
            'location' => 'Downtown',
            'price' => 2500,
            'images' => 'https://placehold.co/600x400?text=Apartment',
            'description' => 'A modern apartment in the heart of the city.',
            'type' => 'apartment',
            'latitude' => 40.5,
            'longitude' => 50.0,
        ]);

        Apartment::create([
            'title' => 'Vinewood Hills Apartment',
            'location' => 'Vinewood Hills',
            'price' => 3200,
            'images' => 'https://placehold.co/600x400?text=Apartment',
            'description' => 'A scenic apartment with a view of the hills.',
            'type' => 'apartment',
            'latitude' => 70.0,
            'longitude' => 60.0,
        ]);
    }
}