<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Citizen;

class CitizenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Citizen::create([ // Create 'Deleted Account' shell account
            'id' => 0,
            'first_name' => "Deleted",
            'last_name' => "Account",
            'email' => "deleted@account.shell",
            'phone_number' => "0000000000",
            'password' => bcrypt('deleted'),
            'province_id' => 12,
            'address' => "Deleted",
            'date_of_birth' => "1990-01-01",
            'gender' => 'prefer Not to Say',
            'profile_picture_path' => "storage/deleted.png"
        ]);
    }
}
