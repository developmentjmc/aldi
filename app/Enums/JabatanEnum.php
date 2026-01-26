<?php

namespace App\Enums;

enum JabatanEnum: string
{
    case MANAGER = 'Manager';
    case STAF = 'Staf';
    case MAGANG = 'Magang';
    case KARYAWAN = 'Karyawan';

    public static function options(): array
    {
        return [
            self::MANAGER->value => 'Manager',
            self::STAF->value => 'Staf',
            self::MAGANG->value => 'Magang',
            self::KARYAWAN->value => 'Karyawan',
        ];
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
