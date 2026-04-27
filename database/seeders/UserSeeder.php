<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. System Admin
        User::updateOrCreate(
            ['email' => 'admin@mail.com'], // The unique identifier to check
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0550123456',
            ]
        );

        // 2. Maintenance Worker
        User::updateOrCreate(
            ['email' => 'worker@mail.com'],
            [
                'name' => 'Maintenance Worker',
                'password' => Hash::make('password123'),
                'role' => 'worker',
                'phone' => '0550123457',
            ]
        );

        // 3. Supply Manager
        User::updateOrCreate(
            ['email' => 'supply@mail.com'],
            [
                'name' => 'Supply Manager',
                'password' => Hash::make('password123'),
                'role' => 'supply_chain',
                'phone' => '0550123458',
            ]
        );
    }
}
