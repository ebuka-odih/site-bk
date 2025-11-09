#!/usr/bin/env php
<?php

// Quick script to create an admin user for testing

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Creating admin user...\n";

$admin = User::create([
    'name' => 'Admin User',
    'email' => 'admin@banko.test',
    'password' => Hash::make('password'),
    'phone' => '+1234567890',
    'is_admin' => true,
    'status' => 'active',
    'account_number' => '1000000001',
    'account_type' => 'savings',
    'balance' => 0,
]);

echo "✅ Admin user created successfully!\n\n";
echo "Login Credentials:\n";
echo "Email: admin@banko.test\n";
echo "Password: password\n\n";
echo "Access the admin panel at: http://banko.test/admin/dashboard\n";

