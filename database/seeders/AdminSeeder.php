<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'id' => 1,
            'first_name' => 'Visoth',
            'last_name' => 'Kim',
            'email' => 'vkim1@paragoniu.edu.kh',
            'phone_number' => '081765103',
            'password' => bcrypt('password'),
            'profile_picture_path' => 'storage/admins/1.png'
        ]);

        Admin::create([
            'id' => 2,
            'first_name' => 'Heang Piv',
            'last_name' => 'Phour',
            'email' => 'hphour@paragoniu.edu.kh',
            'phone_number' => '017391648',
            'password' => bcrypt('password'),
            'profile_picture_path' => 'storage/admins/2.jpg'
        ]);

        Admin::create([
            'id' => 3,
            'first_name' => 'Puthiroth',
            'last_name' => 'Kong',
            'email' => 'pkong3@paragoniu.edu.kh',
            'phone_number' => '078194725',
            'password' => bcrypt('password'),
            'profile_picture_path' => 'storage/admins/3.jpg'
        ]);
    }
}
