<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Console\Command;

class GenerateAccountNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:generate-account-numbers {--force : Force regeneration for all wallets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate account numbers for wallets and create wallets for users without one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating account numbers for wallets...');

        // First, create wallets for users who don't have one
        $usersWithoutWallets = User::doesntHave('wallet')->get();
        
        if ($usersWithoutWallets->isNotEmpty()) {
            $this->info("Found {$usersWithoutWallets->count()} user(s) without wallets. Creating...");
            
            foreach ($usersWithoutWallets as $user) {
                Wallet::create([
                    'user_id' => $user->id,
                    'account_number' => Wallet::generateAccountNumber(),
                    'balance' => 0,
                    'ledger_balance' => 0,
                    'currency' => 'USD',
                    'status' => 'active',
                ]);
            }
            
            $this->info("Created {$usersWithoutWallets->count()} wallet(s).");
        }

        // Then, generate account numbers for wallets without them
        $query = Wallet::query();
        
        if (!$this->option('force')) {
            // Only get wallets without account numbers
            $query->whereNull('account_number');
        }
        
        $wallets = $query->get();

        if ($wallets->isEmpty()) {
            $this->info('No wallets found that need account numbers.');
            return 0;
        }

        $this->info("Found {$wallets->count()} wallet(s) to process.");

        $progressBar = $this->output->createProgressBar($wallets->count());
        $progressBar->start();

        $updated = 0;
        foreach ($wallets as $wallet) {
            $wallet->update([
                'account_number' => Wallet::generateAccountNumber(),
            ]);
            $updated++;
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("Successfully generated account numbers for {$updated} wallet(s).");
        
        return 0;
    }
}
