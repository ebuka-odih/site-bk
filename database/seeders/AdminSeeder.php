<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@shirecommerce.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '+1234567890',
            'balance' => 10000.00,
            'is_admin' => true,
        ]);
    }
}
