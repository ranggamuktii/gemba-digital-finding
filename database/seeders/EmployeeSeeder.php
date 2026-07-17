<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deptProd = \App\Models\Department::where('name', 'Production')->first();
        $deptMaint = \App\Models\Department::where('name', 'Maintenance')->first();

        \App\Models\Employee::insert([
            [
                'username' => 'budi.manager',
                'email' => 'budi@gemba.local',
                'password' => bcrypt('password123'),
                'name' => 'Budi Santoso (LDAP)',
                'department_id' => $deptProd?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'andi.staff',
                'email' => 'andi@gemba.local',
                'password' => bcrypt('password123'),
                'name' => 'Andi Susanto (LDAP)',
                'department_id' => $deptMaint?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
