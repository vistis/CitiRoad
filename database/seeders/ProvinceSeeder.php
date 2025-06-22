<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['id' => 1, 'name' => 'Banteay Meanchey'],
            ['id' => 2, 'name' => 'Battambang'],
            ['id' => 3, 'name' => 'Kampong Cham'],
            ['id' => 4, 'name' => 'Kampong Chhnang'],
            ['id' => 5, 'name' => 'Kampong Speu'],
            ['id' => 6, 'name' => 'Kampong Thom'],
            ['id' => 7, 'name' => 'Kampot'],
            ['id' => 8, 'name' => 'Kandal'],
            ['id' => 9, 'name' => 'Koh Kong'],
            ['id' => 10, 'name' => 'Kratie'],
            ['id' => 11, 'name' => 'Mondul Kiri'],
            ['id' => 12, 'name' => 'Phnom Penh'],
            ['id' => 13, 'name' => 'Preah Vihear'],
            ['id' => 14, 'name' => 'Prey Veng'],
            ['id' => 15, 'name' => 'Pursat'],
            ['id' => 16, 'name' => 'Ratanak Kiri'],
            ['id' => 17, 'name' => 'Siemreap'],
            ['id' => 18, 'name' => 'Preah Sihanouk'],
            ['id' => 19, 'name' => 'Stung Treng'],
            ['id' => 20, 'name' => 'Svay Rieng'],
            ['id' => 21, 'name' => 'Takeo'],
            ['id' => 22, 'name' => 'Oddar Meanchey'],
            ['id' => 23, 'name' => 'Kep'],
            ['id' => 24, 'name' => 'Pailin'],
            ['id' => 25, 'name' => 'Tboung Khmum'],
        ];

        // Insert data directly
        DB::table('provinces')->insert($provinces);
    }
}
