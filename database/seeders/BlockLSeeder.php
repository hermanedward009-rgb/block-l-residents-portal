<?php

namespace Database\Seeders;

use App\Enums\AccountStatus;
use App\Enums\ToiletType;
use App\Enums\UserRole;
use App\Models\CleaningSchedule;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BlockLSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 10) as $number) {
            Room::query()->firstOrCreate(
                ['number' => $number],
                [
                    'label' => "Room {$number}",
                    'allow_multiple_occupants' => true,
                    'max_occupants' => 2,
                ],
            );
        }

        User::query()->firstOrCreate(
            ['email' => 'admin@blockl.local'],
            [
                'name' => 'Block L Administrator',
                'phone' => '+255000000000',
                'room_number' => null,
                'password' => Hash::make('BlockLAdmin2026!'),
                'role' => UserRole::Admin,
                'status' => AccountStatus::Approved,
                'approved_at' => now(),
            ],
        );

        if (CleaningSchedule::query()->exists()) {
            return;
        }

        $rooms = Room::query()->orderBy('number')->get()->values();

        foreach (range(0, 6) as $day) {
            $smallRoom = $rooms[$day % $rooms->count()];
            $largeRoom = $rooms[($day + 1) % $rooms->count()];

            CleaningSchedule::create([
                'room_id' => $smallRoom->id,
                'day_of_week' => $day,
                'toilet_type' => ToiletType::Small,
                'notes' => 'Clean small toilet — shared Block L facility',
            ]);

            CleaningSchedule::create([
                'room_id' => $largeRoom->id,
                'day_of_week' => $day,
                'toilet_type' => ToiletType::Large,
                'notes' => 'Clean large toilet — shared Block L facility',
            ]);
        }
    }
}