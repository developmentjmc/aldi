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

    public static function getWilayah($tipe=null, $search=null, $parentId=null)
    {
        if (empty($tipe)) {
            return null;
        }
        $wilayah = DBHelper::select(<<<SQL
            SELECT current.*
            FROM _wilayah AS current
            WHERE current.tipe = :tipe
            AND (:search = '' OR current.name LIKE CONCAT('%', :search, '%'))
            AND (CAST(:parentId AS BIGINT) IS NULL OR current.id_parent = CAST(:parentId AS BIGINT))
            ORDER BY name
        SQL, [
            'tipe' => $tipe,
            'search' => $search ?? '',
            'parentId' => $parentId,
        ]);
        return $wilayah;
    }

    public static function getBaseFare(): float
    {
        $baseFare = DBHelper::selectOne(<<<SQL
            SELECT description base_fare
            FROM data_masters
            WHERE tipe = 'master' AND name = 'base fare'
        SQL)->base_fare;
        return $baseFare;
    }

    public static function getEmployee($request = null)
    {
        $whereMasaKerja = '';
        if (!empty($request['filter']['masa_kerja'])) {
            $masaKerja = $request['filter']['masa_kerja'];
            if ($masaKerja === '>') {
                $whereMasaKerja = "AND EXTRACT(YEAR FROM AGE(CURRENT_DATE, employees.tanggal_masuk)) > 5";
            }elseif ($masaKerja === '<') {
                $whereMasaKerja = "AND EXTRACT(YEAR FROM AGE(CURRENT_DATE, employees.tanggal_masuk)) < 5";
            }
        }
        $sql = <<<SQL
            SELECT employees.id,
                   employees.nip,
                   employees.name,
                   employees.jabatan,
                   employees.jenis_pegawai,
                   employees.tanggal_masuk,
                   employees.no_hp,
                   employees.email,
                   EXTRACT(YEAR FROM AGE(CURRENT_DATE, employees.tanggal_masuk)) AS masa_kerja
            FROM data_employees AS employees
            WHERE 1=1 {$whereMasaKerja}
        SQL;
        $query = \App\Models\Employee::query();
        $query->from(new \Illuminate\Database\Query\Expression("({$sql}) as employees"));
        return $query;
    }

    public static function getMonth()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }
}