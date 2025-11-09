{{ ... }}

    /**
     * Send a notification for a transaction.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    protected function notifyUser(Transaction $transaction)
    {
        try {
            $transaction->loadMissing(['user', 'recipient']);

            // Only send emails for completed transactions
            if (
                $transaction->status === 'completed' &&
                $transaction->user &&
                $transaction->user->email
            ) {
                \Mail::to($transaction->user->email)->queue(
                    new \App\Mail\TransactionStatusMail($transaction, $transaction->user->name)
                );
            }

            // If this is a transfer, also email the recipient
            if (
                $transaction->type === 'transfer' &&
                $transaction->recipient &&
                $transaction->recipient->email
            ) {
                \Mail::to($transaction->recipient->email)->queue(
                    new \App\Mail\TransactionStatusMail($transaction, $transaction->recipient->name)
                );
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the transaction
            \Log::error('Failed to send transaction email: ' . $e->getMessage());
        }
    }

    /**
     * Transfer money between accounts.
     *
     * @param  \App\Models\User  $sender
     * @param  string  $recipientAccountNumber
     * @param  float  $amount
     * @param  string  $description
     * @param  float  $fee
     * @return \App\Models\Transaction
     * @throws \Exception
     */
    public function transferMoney($sender, $recipientAccountNumber, $amount, $description = '', $fee = 0)
    {
        return DB::transaction(function () use ($sender, $recipientAccountNumber, $amount, $description, $fee) {
            // Find recipient
            $recipient = User::where('account_number', $recipientAccountNumber)
                ->where('status', 'active')
                ->firstOrFail();

            // Check if sender has sufficient balance including fee
            $totalAmount = $amount + $fee;
            if ($sender->balance < $totalAmount) {
                throw new \Exception('Insufficient balance');
            }

            // Create transaction record
            $transaction = new Transaction([
                'user_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'type' => 'transfer',
                'amount' => $amount,
                'fee' => $fee,
                'previous_balance' => $sender->balance,
                'new_balance' => $sender->balance - $totalAmount,
                'status' => 'completed',
                'description' => $description,
                'beneficiary_name' => $recipient->name,
                'beneficiary_account_number' => $recipient->account_number,
                'beneficiary_bank' => $recipient->bank_name,
                'completed_at' => now(),
            ]);

            // Update sender's balance
            $sender->balance -= $totalAmount;
            $sender->save();

            // Update recipient's balance
            $recipient->balance += $amount;
            $recipient->save();

            // Save the transaction
            $transaction->save();
            
            // Send notification
            $this->notifyUser($transaction);

            return $transaction;
        });
    }

    /**
     * Deposit money into an account.
     *
     * @param  \App\Models\User  $user
     * @param  float  $amount
     * @param  string  $description
     * @return \App\Models\Transaction
     */
    public function deposit($user, $amount, $description = '')
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $previousBalance = $user->balance;
            $newBalance = $previousBalance + $amount;

            $transaction = new Transaction([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $amount,
                'fee' => 0,
                'previous_balance' => $previousBalance,
                'new_balance' => $newBalance,
                'status' => 'completed',
                'description' => $description,
                'completed_at' => now(),
            ]);

            $user->balance = $newBalance;
            $user->save();

            $transaction->save();
            
            // Send notification
            $this->notifyUser($transaction);

            return $transaction;
        });
    }

    /**
     * Withdraw money from an account.
     *
     * @param  \App\Models\User  $user
     * @param  float  $amount
     * @param  string  $description
     * @param  float  $fee
     * @return \App\Models\Transaction
     * @throws \Exception
     */
    public function withdraw($user, $amount, $description = '', $fee = 0)
    {
        return DB::transaction(function () use ($user, $amount, $description, $fee) {
            $totalAmount = $amount + $fee;

            if ($user->balance < $totalAmount) {
                throw new \Exception('Insufficient balance');
            }

            $previousBalance = $user->balance;
            $newBalance = $previousBalance - $totalAmount;

            $transaction = new Transaction([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'fee' => $fee,
                'previous_balance' => $previousBalance,
                'new_balance' => $newBalance,
                'status' => 'completed',
                'description' => $description,
                'completed_at' => now(),
            ]);

            $user->balance = $newBalance;
            $user->save();

            $transaction->save();
            
            // Send notification
            $this->notifyUser($transaction);

            return $transaction;
        });
    }

{{ ... }}
