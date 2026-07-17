<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@gemba.com',
                'password' => bcrypt('password'),
                'role' => 'Super Admin'
            ],
            [
                'name' => 'Manager Produksi',
                'email' => 'manager@gemba.com',
                'password' => bcrypt('password'),
                'role' => 'Manager'
            ],
            [
                'name' => 'PIC Maintenance',
                'email' => 'pic@gemba.com',
                'password' => bcrypt('password'),
                'role' => 'PIC'
            ],
            [
                'name' => 'Supervisor',
                'email' => 'verifier@gemba.com',
                'password' => bcrypt('password'),
                'role' => 'Verifier'
            ],
            [
                'name' => 'VP',
                'email' => 'viewer@gemba.com',
                'password' => bcrypt('password'),
                'role' => 'Viewer'
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = \App\Models\User::firstOrCreate(['email' => $userData['email']], $userData);
            $user->assignRole($role);
        }
    }
}
