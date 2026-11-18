<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function icon(): string
    {
        return match ($this->action) {
            'registration' => '👤',
            'user_approved' => '✅',
            'electricity_payment' => '💡',
            'electricity_verified' => '✔️',
            'announcement' => '📢',
            'advice' => '💬',
            'cleaning_update', 'cleaning_complete' => '🚽',
            default => '📋',
        };
    }

    public function accentClass(): string
    {
        return match ($this->action) {
            'registration' => 'activity-accent-amber',
            'user_approved' => 'activity-accent-emerald',
            'electricity_payment' => 'activity-accent-sky',
            'electricity_verified' => 'activity-accent-teal',
            'announcement' => 'activity-accent-violet',
            'advice' => 'activity-accent-slate',
            'cleaning_update', 'cleaning_complete' => 'activity-accent-emerald',
            default => 'activity-accent-navy',
        };
    }
}