<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdviceReaction extends Model
{
    protected $fillable = [
        'advice_post_id',
        'user_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(AdvicePost::class, 'advice_post_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}