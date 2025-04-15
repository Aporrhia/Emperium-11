<?php

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    public function run()
    {
        House::create([
            'title' => 'Rockford Hills Mansion',
            'location' => 'Rockford Hills',
            'price' => 4500,
            'images' => 'https://placehold.co/600x400?text=House',
            'description' => 'A luxurious mansion in an upscale neighborhood.',
            'type' => 'house',
            'latitude' => 45.0,
            'longitude' => 30.0,
        ]);

        House::create([
            'title' => 'Sandy Shores Cottage',
            'location' => 'Sandy Shores',
            'price' => 1500,
            'images' => 'https://placehold.co/600x400?text=House',
            'description' => 'A cozy cottage in a rural area.',
            'type' => 'house',
            'latitude' => 90.0,
            'longitude' => 70.0,
        ]);
    }
}