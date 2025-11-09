<?php

namespace App\Mail;

use App\Models\TransactionCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCodeAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected TransactionCode $code)
    {
        //
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $expiresAt = $this->code->expires_at?->timezone(config('app.timezone', 'UTC'));

        return $this->subject('Your Transaction Authorization Code')
            ->view('emails.transaction_code_assigned')
            ->with([
                'code' => $this->code->code,
                'type' => ucfirst($this->code->type),
                'amount' => $this->formatAmount($this->code->amount),
                'expiresAt' => $expiresAt?->format('M d, Y g:i A T'),
                'notes' => $this->code->notes,
            ]);
    }

    protected function formatAmount($amount): string
    {
        if ($amount === null) {
            return 'Any amount';
        }

        return '$' . number_format((float) $amount, 2);
    }
}

