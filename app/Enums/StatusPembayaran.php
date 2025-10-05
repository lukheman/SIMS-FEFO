<?php

namespace App\Enums;

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

    public function getColor(): string
    {
        return match ($this) {
            self::BELUMBAYAR => 'warning', // kuning → belum dibayar
            self::LUNAS => 'success',      // hijau → sudah lunas
        };
    }
}
