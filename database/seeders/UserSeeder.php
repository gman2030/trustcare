<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. إنشاء الأدمن (Admin)
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '0550123456',
        ]);

        // 2. إنشاء العامل (Worker)
        User::create([
            'name' => 'Maintenance Worker',
            'email' => 'worker@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'worker',
            'phone' => '0550123457', // Added missing phone
        ]);

        // 3. إنشاء السبلاي تشين (Supply Chain)
        User::create([
            'name' => 'Supply Manager',
            'email' => 'supply@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'supply_chain',
            'phone' => '0550123458', // Added missing phone
        ]);
    }
} 
