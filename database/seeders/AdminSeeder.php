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
            'phone_number' => '0123456789',
            'password' => bcrypt('password'),
            'profile_picture_path' => 'storage/admins/vis.png'
        ]);
    }
}
