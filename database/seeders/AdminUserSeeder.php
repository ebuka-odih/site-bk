<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin already exists
        if (User::where('email', 'admin@banko.com')->exists()) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@banko.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'pass_preview' => 'admin123',
            'remember_token' => Str::random(10),
            'account_number' => '10' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT),
            'account_type' => 'savings',
            'phone' => '1234567890',
            'status' => 'active',
            'is_admin' => true,
            'balance' => 1000000, // 10,000.00 in kobo
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@banko.com');
        $this->command->info('Password: admin123');
        $this->command->info('Login URL: ' . url('/login'));
    }
}
