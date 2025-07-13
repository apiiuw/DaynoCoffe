<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Owner Cafe',
            'email' => 'owner@dayno.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Kasir Cafe',
            'email' => 'kasir@dayno.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);

        User::create([
            'name' => 'Manager Cafe',
            'email' => 'manager@dayno.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
        ]);
    }
}