<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $data = [
            [
                'name' => 'ANEKA JAYA NGLN (024-70798885)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'customer',
                'city' => 'Jakarta',
            ],
            [
                'name' => 'MIA',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'customer',
                'city' => 'Surabaya',
            ],
        ];

        \DB::table('users')->insert($data);
    }
}
