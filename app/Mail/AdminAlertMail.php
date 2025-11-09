<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAlertMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected string $title,
        protected string $message,
        protected string $level = 'info',
        protected mixed $model = null
    ) {
        //
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $cleanLevel = strtolower($this->level);
        $levelLabel = match ($cleanLevel) {
            'success' => 'Success',
            'warning' => 'Warning',
            'error', 'danger' => 'Critical',
            'info' => 'Information',
            default => ucfirst($cleanLevel),
        };

        $details = $this->buildDetails();

        return $this->subject('[Admin Alert] ' . $this->title)
            ->view('emails.admin_alert')
            ->with([
                'title' => $this->title,
                'message' => $this->message,
                'level' => $cleanLevel,
                'levelLabel' => $levelLabel,
                'details' => $details,
                'actionUrl' => $this->resolveActionUrl(),
            ]);
    }

    /**
     * Build detail rows from the related model.
     *
     * @return array<int, array{label: string, value: string}>
     */
    protected function buildDetails(): array
    {
        if (!$this->model) {
            return [];
        }

        $hiddenKeys = ['password', 'remember_token', 'api_token', 'two_factor_secret', 'two_factor_recovery_codes'];

        if (method_exists($this->model, 'toArray')) {
            $modelArray = $this->model->toArray();
        } elseif (is_array($this->model)) {
            $modelArray = $this->model;
        } else {
            return [
                [
                    'label' => 'Details',
                    'value' => json_encode($this->model, JSON_PRETTY_PRINT),
                ],
            ];
        }

        $details = [];

        foreach ($modelArray as $key => $value) {
            if (in_array($key, $hiddenKeys, true) || is_array($value) || is_object($value)) {
                continue;
            }

            $details[] = [
                'label' => ucfirst(str_replace('_', ' ', (string) $key)),
                'value' => is_bool($value) ? ($value ? 'Yes' : 'No') : (string) $value,
            ];
        }

        return $details;
    }

    protected function resolveActionUrl(): ?string
    {
        if ($this->model && method_exists($this->model, 'getAdminUrl')) {
            return $this->model->getAdminUrl();
        }

        return null;
    }
}

