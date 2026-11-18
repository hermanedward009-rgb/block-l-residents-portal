<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectricityPeriod extends Model
{
    protected $fillable = [
        'title',
        'amount_tzs',
        'period_start',
        'period_end',
        'payment_deadline',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount_tzs' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
            'payment_deadline' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ElectricityPayment::class);
    }
}