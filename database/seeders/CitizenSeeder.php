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
            'email' => "unknown@email.address",
            'phone_number' => "0000000000",
            'password' => bcrypt('deleted'),
            'province_id' => 12,
            'address' => "Unknown",
            'date_of_birth' => "1990-01-01",
            'gender' => 'Prefer Not to Say',
            'profile_picture_path' => "storage/deleted.svg"
        ]);

        Citizen::create([
            'id' => 1,
            'first_name' => "Rotha",
            'last_name' => "Ung",
            'email' => "rung@proton.me",
            'phone_number' => "018392648",
            'password' => bcrypt('password'),
            'province_id' => 12,
            'address' => "Chrouy Changvar Khan, Kaoh Dach Sangkat, Lvea Village",
            'date_of_birth' => "1982-04-23",
            'gender' => 'Female',
            'profile_picture_path' => "storage/citizens/1.jpg"
        ]);

        Citizen::create([
            'id' => 2,
            'first_name' => "Kimlay",
            'last_name' => "Pak",
            'email' => "kpak@icloud.com",
            'phone_number' => "072891948",
            'password' => bcrypt('password'),
            'province_id' => 12,
            'address' => "Doun Penh Khan, Chakto Mukh Sangkat, Phum 6",
            'date_of_birth' => "1995-06-11",
            'gender' => 'Male',
            'profile_picture_path' => "storage/citizens/2.jpg"
        ]);

        Citizen::create([
            'id' => 3,
            'first_name' => "Kimly",
            'last_name' => "Ty",
            'email' => "kty@gmail.com",
            'phone_number' => "027481936",
            'password' => bcrypt('password'),
            'province_id' => 5,
            'address' => "Phnum Sruoch District, Krang Dei Vay Commune, Krang Kor Village",
            'date_of_birth' => "1990-05-04",
            'gender' => 'Female',
            'profile_picture_path' => "storage/citizens/3.jpg"
        ]);

        Citizen::create([
            'id' => 4,
            'first_name' => "Thong",
            'last_name' => "Ly",
            'email' => "hly@yahoo.com",
            'phone_number' => "097517492",
            'password' => bcrypt('password'),
            'province_id' => 14,
            'address' => "Pur Rieng District, Prey Kanlaong Commune, Popueus Village",
            'date_of_birth' => "1978-11-17",
            'gender' => 'Male',
            'profile_picture_path' => "storage/citizens/4.jpg"
        ]);

        Citizen::create([
            'id' => 5,
            'first_name' => "Both",
            'last_name' => "Somang",
            'email' => "bsomang@hotmail.com",
            'phone_number' => "023749172",
            'password' => bcrypt('password'),
            'province_id' => 8,
            'address' => "Khsach Kandal District, Roka Chonlueng Commune, Tang Ruessei Village",
            'date_of_birth' => "1997-09-13",
            'gender' => 'Male',
            'profile_picture_path' => "storage/citizens/5.jpg"
        ]);

        // Set status
        Citizen::where('id', 0)->update(['status' => 'Restricted']);
        Citizen::where('id', 1)->update(['status' => 'Approved']);
        Citizen::where('id', 2)->update(['status' => 'Pending']);
        Citizen::where('id', 3)->update(['status' => 'Pending']);
        Citizen::where('id', 4)->update(['status' => 'Restricted']);
        Citizen::where('id', 5)->update(['status' => 'Rejected']);
    }
}
