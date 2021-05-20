<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'username' => $faker->word,
                'fullname' => $faker->word,
                'state' => 0,
                'tel' => $faker->word,
                'whatsapp' => $faker->word,
                'token' => $faker->sentence,
                'address' => $faker->word,
                'email' => $faker->email,
                'password' => $faker->word
            ]);
        }
    }
}
