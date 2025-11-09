<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferSentMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected Transaction $transaction,
        protected string $recipientName,
        protected string $recipientAccount,
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
        $metadata = $this->transaction->metadata ?? [];
        $isWireTransfer = ($metadata['transfer_type'] ?? null) === 'wire';

        return $this->subject('Transfer Sent - ' . $amount)
            ->view('emails.transfer_sent')
            ->with([
                'senderName' => $this->transaction->user?->name,
                'recipientName' => $this->recipientName,
                'recipientAccount' => $this->recipientAccount,
                'amount' => $amount,
                'reference' => $this->transaction->reference,
                'date' => $this->transaction->created_at?->timezone(config('app.timezone', 'UTC'))?->format('F j, Y \a\t g:i A'),
                'status' => ucfirst($this->transaction->status),
                'fee' => $this->transaction->fee > 0 ? $this->formatMoney($this->transaction->fee) : null,
                'description' => $this->transaction->description,
                'availableBalance' => $this->formatMoney($this->newBalance),
                'isWireTransfer' => $isWireTransfer,
                'wireDetails' => [
                    'beneficiary_name' => $metadata['beneficiary_name'] ?? null,
                    'bank_name' => $metadata['bank_name'] ?? null,
                    'account_number' => $metadata['account_number'] ?? null,
                    'routing_number' => $metadata['routing_number'] ?? null,
                    'swift_code' => $metadata['swift_code'] ?? null,
                    'beneficiary_address' => $metadata['beneficiary_address'] ?? null,
                ],
            ]);
    }

    protected function formatMoney(int $value): string
    {
        return '$' . number_format($value / 100, 2);
    }
}

