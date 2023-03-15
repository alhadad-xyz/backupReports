<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DistributorDataSeeder extends Seeder
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
                'name' => 'PT. UPINDO RAYA SEMESTA BORNEO (BALIKPAPAN)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Balikpapan',
            ],
            [
                'name' => 'PT. UPINDO RAYA SEMESTA BORNEO (SAMARINDA)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Samarinda',
            ],
            [
                'name' => 'PT. UPINDO RAYA SEMESTA BORNEO (SANGATA)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Sangata',
            ],
            [
                'name' => 'PT. UPINDO RAYA SEMESTA BORNEO (BONTANG)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Bontang',
            ],
            [
                'name' => 'PT. UPINDO RAYA SEMESTA BORNEO (MELAK)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Melak',
            ],
            [
                'name' => 'PT. UPINDO RAYA SEMESTA BORNEO (DEPO)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Depo',
            ],
            [
                'name' => 'PT. HARAPAN SENTOSA RAYA (BANJARMASIN)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Banjarmasin',
            ],
            [
                'name' => 'PT. HARAPAN SENTOSA RAYA (BARABAI)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Barabai',
            ],
            [
                'name' => 'CV. ANUGRAH NUSANTARA',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Pontianak',
            ],
            [
                'name' => 'CV. KAPAS ASIA PRIMA',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Yogyakarta',
            ],
            [
                'name' => 'PT. GRASIA TIMOR ABADI',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Semarang',
            ],
            [
                'name' => 'CV. TJAHYONO ABADI',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Kudus',
            ],
            [
                'name' => 'CV. SINAR PERKASA',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Tegal',
            ],
            [
                'name' => 'PT. SINERGI DISTRIBUSI UTAMA (TULUNG AGUNG)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Tulung Agung',
            ],
            [
                'name' => 'PT. SINERGI DISTRIBUSI UTAMA (KEDIRI)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Kediri',
            ],
            [
                'name' => 'PT. SINERGI DISTRIBUSI UTAMA (BLITAR)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Blitar',
            ],
            [
                'name' => 'CV. ASIA PRIMA DISTRIBUSI',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Solo',
            ],
            [
                'name' => 'PT. MAJU MANDIRI MAPAN',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Malang',
            ],
            [
                'name' => 'UD. GENERAL DISTRIBUTION',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Purwokerto',
            ],
            [
                'name' => 'CV. HAPPY',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Magelang',
            ],
            [
                'name' => 'PT. TUJUH BERLIAN SAKTI (MADIUN)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Madiun',
            ],
            [
                'name' => 'PT. TUJUH BERLIAN SAKTI (LAMONGAN)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Lamongan',
            ],
            [
                'name' => 'PT. TUJUH BERLIAN SAKTI (JOMBANG)',
                'email' => $faker->email,
                'password' => Hash::make('12341234'),
                'type' => 'distributor',
                'city' => 'Jombang',
            ],
        ];

        \DB::table('users')->insert($data);
    }
}
