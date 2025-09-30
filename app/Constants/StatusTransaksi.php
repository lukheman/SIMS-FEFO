<?php

namespace App\Constants;

enum StatusTransaksi: string
{
    case PENDING = 'pending';
    case DIPROSES = 'diproses';
    case DIKIRIM = 'dikirim';
    case DITOLAK = 'ditolak';
    case DITERIMA = 'diterima';
    case SELESAI = 'selesai';
    case BATAL = 'batal';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
