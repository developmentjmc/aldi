<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use jeemce\helpers\DBHelper;

class DataHelper
{
    public static function getStatusOptions(): array
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
        ];
    }

    public static function getWilayah($tipe=null)
    {
        if (empty($tipe)) {
            return null;
        }
        $wilayah = DBHelper::select(<<<SQL
            SELECT id, id_parent, name
            FROM data_masters
            WHERE tipe = :tipe
            ORDER BY name
        SQL, [
            'tipe' => $tipe,
        ]);
        return $wilayah;
    }

    public static function getBaseFare(): float
    {
        $baseFare = DBHelper::selectOne(<<<SQL
            SELECT description base_fare
            FROM data_masters
            WHERE tipe = 'base fare'
        SQL)->base_fare;
        return $baseFare;
    }
}