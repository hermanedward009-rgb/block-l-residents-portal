<?php

namespace App\Models;

use App\Enums\ToiletType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CleaningSchedule extends Model
{
    protected $fillable = [
        'room_id',
        'day_of_week',
        'toilet_type',
        'scheduled_date',
        'is_completed',
        'completed_at',
        'completed_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'toilet_type' => ToiletType::class,
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public static function dayName(int $day): string
    {
        return ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$day] ?? '';
    }

    public function assigneeLabelFor(?User $viewer = null): string
    {
        $room = $this->room;
        if (! $room) {
            return 'Unassigned';
        }

        if ($viewer && $viewer->room_number === $room->number) {
            return $viewer->name;
        }

        return $room->assigneeLabel();
    }

    public function isDutyFor(?User $user): bool
    {
        return $user && $user->room_number && $this->room?->number === $user->room_number;
    }
}