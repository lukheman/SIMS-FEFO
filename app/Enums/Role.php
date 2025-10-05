<?php

namespace App\Enums;

enum Role: string
{
    case ADMINTOKO = 'admintoko';
    case ADMINGUDANG = 'admingudang';
    case PIMPINAN = 'pimpinan';
    case RESELLER = 'reseller';
    case KURIR = 'kurir';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
