<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Category;
use App\Models\RiskLevel;
use App\Models\Finding;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RealManufacturingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Setup Realistic Areas
        $factoryAreas = [
            'Line 1 - Stamping',
            'Line 2 - Welding A',
            'Line 3 - Welding B',
            'Line 4 - Machining (CNC)',
            'Line 5 - Assembling (Final)',
            'Quality Control Lab',
            'Material Warehouse',
            'Finished Goods Storage',
            'Utility (Compressor Room)'
        ];

        foreach ($factoryAreas as $areaName) {
            Area::firstOrCreate(['name' => $areaName]);
        }

        // 2. Setup Realistic Categories
        $categories = [
            'Safety Hazard',
            'Quality Defect',
            '5S / Housekeeping',
            'Machine Breakdown',
            'Process Deviation',
            'Material Shortage'
        ];

        foreach ($categories as $catName) {
            Category::firstOrCreate(['name' => $catName]);
        }

        // 3. Setup Realistic Risk Levels
        $riskLevels = [
            ['name' => 'Minor', 'sla_hours' => 48],
            ['name' => 'Major', 'sla_hours' => 24],
            ['name' => 'Critical', 'sla_hours' => 12],
            ['name' => 'Catastrophic', 'sla_hours' => 4],
        ];

        foreach ($riskLevels as $risk) {
            RiskLevel::updateOrCreate(['name' => $risk['name']], $risk);
        }

        // Generate 50 realistic findings
        $areas = Area::all();
        $cats = Category::all();
        $risks = RiskLevel::all();
        $manager = User::role('Manager')->first() ?? User::first();
        $pic = User::role('PIC')->first() ?? User::first();
        
        $problems = [
            "Terdapat ceceran oli di lantai samping mesin press 500T, sangat licin dan berbahaya bagi operator.",
            "Lampu penerangan di area inspeksi akhir mati 2 buah, lux meter menunjukkan di bawah standar 500 lux.",
            "Cover mesin conveyor penggerak terbuka, roda gigi terlihat jelas. Potensi tangan terjepit sangat tinggi.",
            "Operator tidak menggunakan sarung tangan anti-gores saat menangani part sheet metal tajam.",
            "Ditemukan part NG (No Good) tercampur dengan part OK di dalam box material siap rakit.",
            "Suara bising abnormal (kasar) terdengar dari motor spindle CNC mesin nomor 3.",
            "Tumpukan box material terlalu tinggi (melebihi batas aman 1.5 meter), berpotensi roboh.",
            "Kabel grounding mesin las robotik terkelupas, risiko tersengat listrik saat mesin menyala.",
            "Suhu di dalam ruang compressor terlalu panas (mencapai 45 derajat), kipas exhaust mati total.",
            "Jalur forklift terhalang oleh palet kosong yang ditaruh sembarangan, mengganggu kelancaran logistik."
        ];

        $statuses = ['OPEN', 'IN_PROGRESS', 'WAITING_VERIFICATION', 'CLOSED', 'OVERDUE'];

        for ($i = 0; $i < 50; $i++) {
            $createdDate = Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 23));
            $selectedRisk = $risks->random();
            $status = $statuses[array_rand($statuses)];
            
            // Generate sequence manually to bypass observer during seeding for speed
            $month = $createdDate->format('Ym');
            $count = Finding::where('finding_no', 'like', "GDF-{$month}-%")->count() + 1;
            $findingNo = sprintf("GDF-%s-%04d", $month, $count);

            $finding = Finding::create([
                'finding_no' => $findingNo,
                'category_id' => $cats->random()->id,
                'area_id' => $areas->random()->id,
                'risk_level_id' => $selectedRisk->id,
                'location' => 'Zona ' . rand(1, 5) . ' / Mesin ' . Str::upper(Str::random(3)) . '-' . rand(10, 99),
                'description' => $problems[array_rand($problems)],
                'status' => $status,
                'created_by' => $manager->id,
                'assigned_to' => rand(0, 1) ? $pic->id : null,
                'due_date' => (clone $createdDate)->addHours($selectedRisk->sla_hours),
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            // Simulate histories based on status
            if ($status !== 'OPEN') {
                $finding->histories()->create([
                    'old_status' => 'OPEN',
                    'new_status' => 'IN_PROGRESS',
                    'changed_by' => $pic->id,
                    'remark' => 'Sedang dicek ke lokasi',
                    'created_at' => (clone $createdDate)->addHours(1),
                ]);

                if (in_array($status, ['WAITING_VERIFICATION', 'CLOSED'])) {
                    $finding->actions()->create([
                        'action_description' => 'Telah dilakukan perbaikan dan penggantian part yang rusak.',
                        'performed_by' => $pic->id,
                        'action_date' => (clone $createdDate)->addHours(2),
                        'created_at' => (clone $createdDate)->addHours(2),
                    ]);

                    $finding->histories()->create([
                        'old_status' => 'IN_PROGRESS',
                        'new_status' => 'WAITING_VERIFICATION',
                        'changed_by' => $pic->id,
                        'remark' => 'Menunggu verifikasi manager',
                        'created_at' => (clone $createdDate)->addHours(2),
                    ]);
                }

                if ($status === 'CLOSED') {
                    $finding->histories()->create([
                        'old_status' => 'WAITING_VERIFICATION',
                        'new_status' => 'CLOSED',
                        'changed_by' => $manager->id,
                        'remark' => 'Verifikasi OK, sudah diperbaiki dengan baik.',
                        'created_at' => (clone $createdDate)->addHours(3),
                    ]);
                }
            }
        }
    }
}
