<?php

namespace App\Constants;

enum Role: string
{
    case ADMINTOKO = 'admintoko';
    case ADMINGUDANG = 'admingudang';
    case PEMILIKTOKO = 'pemiliktoko';
    case RESELLER = 'reseller';
    case KURIR = 'kurir';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
