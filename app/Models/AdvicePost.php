<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvicePost extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'category',
        'body',
        'is_removed',
    ];

    protected function casts(): array
    {
        return [
            'is_removed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AdviceComment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(AdviceReaction::class);
    }
}