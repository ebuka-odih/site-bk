<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionStatusMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected Transaction $transaction,
        protected string $recipientName
    ) {
        $this->transaction->loadMissing('recipient');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $type = ucfirst($this->transaction->type);
        $status = ucfirst($this->transaction->status);
        $amount = $this->formatAmount($this->transaction->amount);
        $subject = "{$type} {$status}: {$amount}";

        return $this->subject($subject)
            ->view('emails.transaction_status')
            ->with([
                'recipientName' => $this->recipientName,
                'type' => $type,
                'status' => $status,
                'amount' => $amount,
                'reference' => $this->transaction->reference,
                'createdAt' => $this->transaction->created_at?->timezone(config('app.timezone', 'UTC'))?->format('M j, Y \a\t g:i A'),
                'fee' => $this->transaction->fee > 0 ? $this->formatAmount($this->transaction->fee) : null,
                'description' => $this->transaction->description,
                'beneficiaryName' => $this->transaction->beneficiary_name ?? $this->transaction->recipient?->name,
                'beneficiaryAccount' => $this->transaction->beneficiary_account_number ?? $this->transaction->recipient?->account_number,
                'availableBalance' => $this->transaction->new_balance ? $this->formatAmount($this->transaction->new_balance) : null,
                'actionUrl' => url("/transactions/{$this->transaction->id}"),
            ]);
    }

    protected function formatAmount(null|int|float|string $value): string
    {
        $numeric = (float) $value;

        return '₦' . number_format($numeric, 2);
    }
}

