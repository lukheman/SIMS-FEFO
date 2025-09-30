<?php

namespace App\Constants;

enum UnitKecilProduk: string
{
    case PCS = 'pcs';
    case BOTOL = 'botol';
    case KILOGRAM = 'kilogram';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
