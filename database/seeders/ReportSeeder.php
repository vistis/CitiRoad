<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Report::create([
            'title' => "Dangerous Potholes and Flooding on Rural Road",
            'province_id' => 5,
            'description' => "The unpaved road in our village becomes almost impassable during the rainy season due to severe potholes and consistent flooding. Motorbikes and even cars frequently get stuck, and it's dangerous for children walking to school. Emergency services would struggle to access the area. Repairs are constantly needed, but the current patch-up work doesn't last.",
            'address' => "Phnum Sruoch District, Krang Dei Vay Commune, Krang Kor Village, Unnamed Street (Main Street that Cuts Through the Village)",
            'citizen_id' => 3,
        ]);

        Report::create([
            'title' => "Non-Functional Streetlights",
            'province_id' => 12,
            'description' => "The streetlights broke down, causing inconvenience to residents and making it difficult for them to navigate at night.",
            'address' => "Daun Penh Khan, Chey Chumneah Sangkat, Phum 2, Street Sisowath Quay",
            'citizen_id' => 1,
        ]);

        Report::create([
            'title' => "Persistent Traffic Congestion",
            'province_id' => 8,
            'description' => "The intersection near the market experiences severe traffic jams daily, especially during peak hours, largely due to inadequate road capacity and disorganized turning lanes.",
            'address' => "Angk Snoul District, Baek Chan Commune, National Road 4 (Baek Chan Market)",
            'citizen_id' => 3,
        ]);

        Report::create([
            'title' => "Lack of Pedestrian Crossings and Dangerous Driving Near School",
            'province_id' => 5,
            'description' => "There are no marked pedestrian crossings or traffic lights near the primary school in our village, making it extremely dangerous for children to cross the busy national road. Drivers frequently speed, and there have been several near-misses.",
            'address' => "Samraong Tong District, Voa sor, Chambak Village, National Road 4 (Chambak Primary School)",
            'citizen_id' => 3,
        ]);

        Report::create([
            'title' => "Broken Traffic Light at Major Intersection Causing Chaos",
            'province_id' => 12,
            'description' => "The traffic light at the intersection of Street 271 and Monivong Boulevard has been out of order for the past three days. This is a major intersection, and the lack of a functioning traffic light has led to extreme congestion, near-constant honking, and several minor accidents. It's incredibly dangerous for both drivers and pedestrians, and it needs urgent repair.",
            'address' => "Daun Penh Khan, Chey Chumneah Sangkat, Phum 2, Street Sisowath Quay",
            'citizen_id' => 1,
        ]);

        // Add images
        DB::table('report_images')->insert([
            ['type' => 'Before', 'image_path' => "reports/1-before-0.jpg", 'report_id' => 1],
            ['type' => 'Before', 'image_path' => "reports/1-before-1.jpg", 'report_id' => 1],
            ['type' => 'Before', 'image_path' => "reports/2-before-1.jpg", 'report_id' => 2],
            ['type' => 'Before', 'image_path' => "reports/3-before-0.jpg", 'report_id' => 3],
            ['type' => 'Before', 'image_path' => "reports/3-before-1.jpg", 'report_id' => 3],
            ['type' => 'Before', 'image_path' => "reports/3-before-2.jpg", 'report_id' => 3],
            ['type' => 'Before', 'image_path' => "reports/4-before-0.jpg", 'report_id' => 4],
            ['type' => 'Before', 'image_path' => "reports/5-before-0.jpg", 'report_id' => 5],
            ['type' => 'After', 'image_path' => "reports/5-after-0.jpg", 'report_id' => 5],
        ]);

        // Set status
        Report::where('id', 2)->update(['status' => 'Rejected', 'updated_by' => 3, 'Remark' => "The streetlights were found to be functioning properly."]);
        Report::where('id', 3)->update(['status' => 'Investigating', 'updated_by' => 5, 'Remark' => "Investigation team dispatched."]);
        Report::where('id', 4)->update(['status' => 'Resolving', 'updated_by' => 4, 'Remark' => "Issue confirmed. Proceeding with repair."]);
        Report::where('id', 5)->update(['status' => 'Resolved', 'updated_by' => 1, 'Remark' => "Officially resolved."]);
    }
}
