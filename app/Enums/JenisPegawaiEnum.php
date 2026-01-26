<?php

namespace App\Enums;

enum JenisPegawaiEnum: string
{
    case KONTRAK = 'Kontrak';
    case TETAP = 'Tetap';
    case MAGANG = 'Magang';

    public static function options(): array
    {
        return [
            self::KONTRAK->value => 'Kontrak',
            self::TETAP->value => 'Tetap',
            self::MAGANG->value => 'Magang',
        ];
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
