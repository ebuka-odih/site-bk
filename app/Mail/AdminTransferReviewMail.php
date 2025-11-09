<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminTransferReviewMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected Transaction $transaction)
    {
        $this->transaction->loadMissing(['user', 'recipient', 'user.wallet']);
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $metadata = $this->transaction->metadata ?? [];
        $isWire = ($metadata['transfer_type'] ?? null) === 'wire';

        return $this->subject('Transfer Pending Approval - ' . $this->transaction->reference)
            ->view('emails.admin_transfer_review')
            ->with([
                'transaction' => $this->transaction,
                'user' => $this->transaction->user,
                'recipient' => $this->transaction->recipient,
                'amount' => $this->formatMoney($this->transaction->amount),
                'fee' => $this->transaction->fee > 0 ? $this->formatMoney($this->transaction->fee) : null,
                'totalDebit' => $this->formatMoney($this->transaction->amount + $this->transaction->fee),
                'metadata' => $metadata,
                'isWireTransfer' => $isWire,
                'createdAt' => $this->transaction->created_at?->timezone(config('app.timezone', 'UTC'))?->format('M d, Y g:i A T'),
                'walletBalance' => $this->transaction->user?->wallet
                    ? $this->formatMoney($this->transaction->user->wallet->balance)
                    : null,
            ]);
    }

    protected function formatMoney(int $valueInKobo): string
    {
        return '$' . number_format($valueInKobo / 100, 2);
    }
}

