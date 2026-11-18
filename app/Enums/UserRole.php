<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Resident = 'resident';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Resident => 'Resident',
        };
    }
}