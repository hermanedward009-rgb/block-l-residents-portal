<?php

namespace App\Enums;

enum ToiletType: string
{
    case Small = 'small';
    case Large = 'large';

    public function label(): string
    {
        return match ($this) {
            self::Small => __('portal.toilet_small'),
            self::Large => __('portal.toilet_large'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Small => 'bg-sky-100 text-sky-800',
            self::Large => 'bg-violet-100 text-violet-800',
        };
    }
}