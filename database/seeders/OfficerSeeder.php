<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Officer;

class OfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Officer::create([
            'id' => 1,
            'first_name' => "Livoth",
            'last_name' => "Kim",
            'email' => "lkim@phnompenh.gov.kh",
            'phone_number' => "017428491",
            'password' => bcrypt('password'),
            'role' => 'Municipality Head',
            'province_id' => 12,
            'profile_picture_path' => 'officers/1.jpg',
        ]);

        Officer::create([
            'id' => 2,
            'first_name' => "Limhout",
            'last_name' => "Peng",
            'email' => "lpeng@phnompenh.gov.kh",
            'phone_number' => "098518362",
            'password' => bcrypt('password'),
            'role' => 'Municipality Deputy',
            'province_id' => 12,
            'profile_picture_path' => 'officers/2.jpg',
        ]);

        Officer::create([
            'id' => 3,
            'first_name' => "Thong",
            'last_name' => "Heng",
            'email' => "theng@phnompenh.gov.kh",
            'phone_number' => "068917365",
            'password' => bcrypt('password'),
            'role' => 'Municipality Deputy',
            'province_id' => 12,
            'profile_picture_path' => 'officers/3.jpg',
        ]);

        Officer::create([
            'id' => 4,
            'first_name' => "Pov",
            'last_name' => "Leang",
            'email' => "pleang@kampongspeu.gov.kh",
            'phone_number' => "077557183",
            'password' => bcrypt('password'),
            'role' => 'Municipality Head',
            'province_id' => 5,
            'profile_picture_path' => 'officers/4.jpg',
        ]);

        Officer::create([
            'id' => 5,
            'first_name' => "Theara",
            'last_name' => "Yong",
            'email' => "tyong@kandal.gov.kh",
            'phone_number' => "078562976",
            'password' => bcrypt('password'),
            'role' => 'Municipality Head',
            'province_id' => 8,
            'profile_picture_path' => 'officers/5.jpg',
        ]);
    }
}
