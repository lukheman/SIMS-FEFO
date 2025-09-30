<?php

namespace App\Constants;

enum UnitBesarProduk: string
{
    case DOS = 'dos';
    case BAL = 'bal';
    case PACK = 'pack';
    case RENTENG = 'renteng';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
