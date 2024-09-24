<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('blogs')->insert([
                'title' => $faker->sentence,
                'body' => $faker->paragraph,
                'author_name' => $faker->name,
                'user_id' => rand(1, 10),
            ]);
        }
    }
}
