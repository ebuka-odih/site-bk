<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'type',
        'name',
        'key',
        'enabled',
        'min_amount',
        'max_amount',
        'processing_time',
        'fee_percentage',
        'fee_fixed',
        'configuration',
        'instructions',
        'notes',
        'requires_reference',
        'sort_order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'requires_reference' => 'boolean',
        'min_amount' => 'integer',
        'max_amount' => 'integer',
        'fee_fixed' => 'integer',
        'fee_percentage' => 'decimal:2',
        'configuration' => 'array',
        'instructions' => 'array',
        'notes' => 'array',
    ];

    // Scopes
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Helper method to get formatted configuration
    public function getFormattedConfig()
    {
        $config = [
            'name' => $this->name,
            'enabled' => $this->enabled,
            'min_amount' => $this->min_amount,
            'processing_time' => $this->processing_time,
            'requires_reference' => $this->requires_reference,
        ];

        if ($this->max_amount) {
            $config['max_amount'] = $this->max_amount;
        }

        if ($this->fee_percentage) {
            $config['fee_percentage'] = (float) $this->fee_percentage;
        }

        if ($this->fee_fixed) {
            $config['fee'] = $this->fee_fixed;
        }

        if ($this->configuration) {
            $config = array_merge($config, $this->configuration);
        }

        if ($this->instructions) {
            $config['instructions'] = $this->instructions;
        }

        if ($this->notes) {
            $config['notes'] = $this->notes;
        }

        return $config;
    }
}
