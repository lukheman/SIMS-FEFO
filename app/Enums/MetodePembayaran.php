<?php

namespace App\Enums;

enum MetodePembayaran: string
{
    case COD = 'cod';
    case TRANSFER = 'transfer';
    case TUNAI = 'tunai';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
