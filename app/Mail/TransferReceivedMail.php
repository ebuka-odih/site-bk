<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected Transaction $transaction,
        protected string $senderName,
        protected string $senderAccount,
        protected int $newBalance
    ) {
        $this->transaction->loadMissing('user');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $amount = $this->formatMoney($this->transaction->amount);

        return $this->subject('Money Received - ' . $amount)
            ->view('emails.transfer_received')
            ->with([
                'recipientName' => $this->transaction->user?->name,
                'senderName' => $this->senderName,
                'senderAccount' => $this->senderAccount,
                'amount' => $amount,
                'reference' => $this->transaction->reference,
                'date' => $this->transaction->created_at?->timezone(config('app.timezone', 'UTC'))?->format('F j, Y \a\t g:i A'),
                'status' => ucfirst($this->transaction->status),
                'availableBalance' => $this->formatMoney($this->newBalance),
            ]);
    }

    protected function formatMoney(int $value): string
    {
        return '$' . number_format($value / 100, 2);
    }
}

