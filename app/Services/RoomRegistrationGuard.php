<?php

namespace App\Services;

use App\Enums\AccountStatus;
use App\Models\Room;
use App\Models\User;

class RoomRegistrationGuard
{
    public static function roomAvailable(int $roomNumber, ?int $exceptUserId = null): bool
    {
        $room = Room::query()->where('number', $roomNumber)->first();

        if (! $room) {
            return false;
        }

        $max = $room->max_occupants ?? 2;

        $occupied = User::query()
            ->where('room_number', $roomNumber)
            ->whereIn('status', [AccountStatus::Approved, AccountStatus::Pending])
            ->when($exceptUserId, fn ($q) => $q->where('id', '!=', $exceptUserId))
            ->count();

        return $occupied < $max;
    }

    public static function roomOccupancy(int $roomNumber): array
    {
        $room = Room::query()->where('number', $roomNumber)->first();
        $max = $room?->max_occupants ?? 2;
        $count = User::query()
            ->where('room_number', $roomNumber)
            ->whereIn('status', [AccountStatus::Approved, AccountStatus::Pending])
            ->count();

        return ['count' => $count, 'max' => $max];
    }
}