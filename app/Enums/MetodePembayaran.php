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

    /**
     * Mengembalikan warna yang sesuai dengan metode pembayaran.
     * Warna bisa digunakan untuk badge atau status UI.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::COD => 'warning',   // misalnya kuning
            self::TRANSFER => 'primary', // biru
            self::TUNAI => 'success',   // hijau
        };
    }
}
