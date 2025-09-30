<?php

namespace App\Constants;

enum StatusPembayaran: string
{
    case BELUMBAYAR = 'belum_bayar';
    case LUNAS = 'lunas';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function label(): string
    {
        return ucwords(str_replace('_', ' ', $this->value));
    }
}
