<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::where('email', 'admin.tc@gmail.com')->delete();
    $this->call(UserSeeder::class);
    \App\Models\User::create([
        'name' => 'Super Admin',
        'email' => 'admin.tc@gmail.com',
        'phone' => '00000000',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role' => 'admin',
    ]);
}

}
