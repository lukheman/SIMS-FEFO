<?php

namespace App\Enums;

enum StatusTransaksi: string
{
    case PENDING = 'pending';
    case DIPROSES = 'diproses';
    case DIKIRIM = 'dikirim';
    case DITOLAK = 'ditolak';
    case DITERIMA = 'diterima';
    case SELESAI = 'selesai';
    case BATAL = 'batal';

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::DIPROSES => 'primary',
            self::DIKIRIM => 'info',
            self::DITERIMA => 'success',
            self::SELESAI => 'secondary',
            self::DITOLAK, self::BATAL => 'danger',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
