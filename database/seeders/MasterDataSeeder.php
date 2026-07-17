<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['Production', 'Quality Control', 'Maintenance', 'Warehouse'];
        foreach ($departments as $dept) {
            \App\Models\Department::firstOrCreate(['name' => $dept]);
        }

        $areas = ['Machining Line 1', 'Assembly Line 1', 'Warehouse'];
        foreach ($areas as $area) {
            \App\Models\Area::firstOrCreate(['name' => $area]);
        }

        $categories = ['Safety', 'Quality', '5S', 'Productivity'];
        foreach ($categories as $cat) {
            \App\Models\Category::firstOrCreate(['name' => $cat]);
        }

        $riskLevels = [
            ['name' => 'Low', 'sla_hours' => 48],
            ['name' => 'Medium', 'sla_hours' => 24],
            ['name' => 'High', 'sla_hours' => 12],
            ['name' => 'Critical', 'sla_hours' => 4],
        ];
        foreach ($riskLevels as $risk) {
            \App\Models\RiskLevel::firstOrCreate(['name' => $risk['name']], $risk);
        }
    }
}
