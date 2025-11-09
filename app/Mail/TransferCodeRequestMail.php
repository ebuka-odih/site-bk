<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferCodeRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public array $transferDetails;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, array $transferDetails)
    {
        $this->user = $user;
        $this->transferDetails = $transferDetails;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Transfer Code Request - ' . $this->user->name)
            ->view('emails.transfer_code_request')
            ->with([
                'user' => $this->user,
                'transferDetails' => $this->transferDetails,
            ]);
    }
}

