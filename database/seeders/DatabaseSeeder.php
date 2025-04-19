<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FakerSeeder::class,
            HouseSeeder::class,
            ApartmentSeeder::class,
        ]);
    }
}
