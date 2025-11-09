<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'actor_id',
        'event',
        'auditable_type',
        'auditable_id',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action.
     */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Get the auditable model.
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Log an event.
     */
    public static function logEvent($event, $details = [], $auditable = null)
    {
        return self::create([
            'actor_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable ? $auditable->id : null,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
