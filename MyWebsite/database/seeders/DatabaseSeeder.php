<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call the individual seeders
        $this->call([
            UserSeeder::class,
            BlogSeeder::class,
            TagSeeder::class,
        ]);
    }
}
