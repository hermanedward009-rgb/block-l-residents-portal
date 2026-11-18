<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\PortalNotification;
use App\Models\User;
use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Notifications\GenericPortalPush;
use Illuminate\Support\Collection;

class PortalNotifier
{
    public static function notifyApprovedResidents(
        string $type,
        string $title,
        string $body,
        ?array $data = null,
        ?User $except = null,
        ?int $onlyRoomNumber = null,
    ): void {
        $users = User::query()
            ->where('role', UserRole::Resident)
            ->where('status', AccountStatus::Approved)
            ->when($onlyRoomNumber, fn ($q) => $q->where('room_number', $onlyRoomNumber))
            ->when($except, fn ($q) => $q->where('id', '!=', $except->id))
            ->get();

        self::notifyUsers($users, $type, $title, $body, $data);
    }

    public static function notifyAdmins(
        string $type,
        string $title,
        string $body,
        ?array $data = null,
    ): void {
        $users = User::query()
            ->where('role', UserRole::Admin)
            ->where('status', AccountStatus::Approved)
            ->get();

        self::notifyUsers($users, $type, $title, $body, $data);
    }

    public static function notifyUser(
        User $user,
        string $type,
        string $title,
        string $body,
        ?array $data = null,
    ): void {
        self::notifyUsers(collect([$user]), $type, $title, $body, $data);
    }

    protected static function notifyUsers(
        Collection $users,
        string $type,
        string $title,
        string $body,
        ?array $data,
    ): void {
        foreach ($users as $user) {
            PortalNotification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'data' => $data,
            ]);

            $user->notify(new GenericPortalPush($title, $body, $data ?? []));
        }
    }

    public static function log(?User $user, string $action, string $description, ?array $metadata = null): void
    {
        ActivityLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }
}