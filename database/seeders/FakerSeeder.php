<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('apartments')->insert([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(),
                'location' => $faker->city(),
                'price' => $faker->randomFloat(2, 500, 5000),
                'type' => 'apartment',
                'images' => $faker->imageUrl(640, 480, 'apartment'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('houses')->insert([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(),
                'location' => $faker->city(),
                'price' => $faker->randomFloat(2, 1000, 10000),
                'type' => 'house',
                'images' => $faker->imageUrl(640, 480, 'house'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('offices')->insert([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(),
                'location' => $faker->city(),
                'price' => $faker->randomFloat(2, 2000, 20000),
                'type' => 'office',
                'images' => $faker->imageUrl(640, 480, 'office'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('warehouses')->insert([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(),
                'location' => $faker->city(),
                'price' => $faker->randomFloat(2, 5000, 50000),
                'type' => 'warehouse',
                'images' => $faker->imageUrl(640, 480, 'warehouse'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('bunkers')->insert([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(),
                'location' => $faker->city(),
                'price' => $faker->randomFloat(2, 10000, 100000),
                'type' => 'bunker',
                'images' => $faker->imageUrl(640, 480, 'bunker'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('facilities')->insert([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(),
                'location' => $faker->city(),
                'price' => $faker->randomFloat(2, 15000, 150000),
                'type' => 'facility',
                'images' => $faker->imageUrl(640, 480, 'facility'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $classes = ['car', 'motorcycle', 'boat', 'aircraft'];
        $manufacturers = ['Luxor', 'Vapid', 'Dinka', 'Pegassi'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('transport')->insert([
                'title' => $faker->sentence(2),
                'description' => $faker->paragraph(),
                'price' => $faker->randomFloat(2, 500, 50000),
                'class' => $faker->randomElement($classes),
                'manufacturer' => $faker->randomElement($manufacturers),
                'type' => 'transport',
                'images' => $faker->imageUrl(640, 480, 'vehicle'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}