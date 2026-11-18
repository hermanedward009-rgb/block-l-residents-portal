<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'target_room_number',
    ];

    public function isForAllRooms(): bool
    {
        return $this->target_room_number === null;
    }

    public function audienceLabel(): string
    {
        if ($this->isForAllRooms()) {
            return 'All rooms';
        }

        $room = Room::query()->where('number', $this->target_room_number)->first();

        return $room?->displayName() ?? 'Room '.$this->target_room_number;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}