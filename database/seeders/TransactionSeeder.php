<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        // Create sample transactions
        $transactions = [
            [
                'user_id' => $users->first()->id,
                'type' => 'deposit',
                'amount' => 100000, // $1000.00 in kobo
                'reference' => 'TXN-' . time() . '-001',
                'description' => 'Initial account deposit',
                'status' => 'completed',
            ],
            [
                'user_id' => $users->first()->id,
                'type' => 'withdrawal',
                'amount' => 25000, // $250.00 in kobo
                'reference' => 'TXN-' . time() . '-002',
                'description' => 'ATM withdrawal',
                'status' => 'completed',
            ],
            [
                'user_id' => $users->first()->id,
                'type' => 'deposit',
                'amount' => 50000, // $500.00 in kobo
                'reference' => 'TXN-' . time() . '-003',
                'description' => 'Salary deposit',
                'status' => 'completed',
            ],
            [
                'user_id' => $users->first()->id,
                'type' => 'withdrawal',
                'amount' => 10000, // $100.00 in kobo
                'reference' => 'TXN-' . time() . '-004',
                'description' => 'Online purchase',
                'status' => 'pending',
            ],
            [
                'user_id' => $users->first()->id,
                'type' => 'transfer',
                'amount' => 7500, // $75.00 in kobo
                'reference' => 'TXN-' . time() . '-005',
                'description' => 'Transfer to savings account',
                'status' => 'completed',
            ],
        ];

        // Create additional transactions for other users if they exist
        if ($users->count() > 1) {
            $additionalTransactions = [
                [
                    'user_id' => $users->skip(1)->first()->id,
                    'type' => 'deposit',
                    'amount' => 200000, // $2000.00 in kobo
                    'reference' => 'TXN-' . time() . '-006',
                    'description' => 'Business account deposit',
                    'status' => 'completed',
                ],
                [
                    'user_id' => $users->skip(1)->first()->id,
                    'type' => 'withdrawal',
                    'amount' => 15000, // $150.00 in kobo
                    'reference' => 'TXN-' . time() . '-007',
                    'description' => 'Office supplies purchase',
                    'status' => 'completed',
                ],
                [
                    'user_id' => $users->skip(1)->first()->id,
                    'type' => 'deposit',
                    'amount' => 75000, // $750.00 in kobo
                    'reference' => 'TXN-' . time() . '-008',
                    'description' => 'Client payment received',
                    'status' => 'pending',
                ],
            ];
            
            $transactions = array_merge($transactions, $additionalTransactions);
        }

        foreach ($transactions as $transactionData) {
            Transaction::create($transactionData);
        }

        $this->command->info('Created ' . count($transactions) . ' sample transactions.');
    }
}