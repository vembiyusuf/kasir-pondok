<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Bang Saiful',
            'email' => 'saiful@ppba.com',
            'password' => Hash::make(value: 'ppba2025'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Vembi Yusuf',
            'email' => 'vembisaputra273@gmail.com',
            'password' => Hash::make('ppba2025'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Shaim',
            'email' => 'shaim@ppba.com',
            'password' => Hash::make('shaim2025'),
            'role' => 'kasir',
        ]);
    }
}
