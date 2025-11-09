<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransactionCode extends Model
{
    protected $fillable = [
        'code',
        'type',
        'amount',
        'created_by',
        'used_by',
        'transaction_id',
        'expires_at',
        'is_used',
        'used_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    /**
     * Get the admin who created this code.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who used this code.
     */
    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Get the transaction associated with this code.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Check if code is expired.
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if code is valid.
     */
    public function isValid()
    {
        return !$this->is_used && !$this->isExpired();
    }

    /**
     * Generate a unique transaction code.
     */
    public static function generateCode()
    {
        do {
            $code = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
