<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use jeemce\helpers\DBHelper;
use jeemce\models\CrudTrait as ModelsCrudTrait;
use jeemce\models\MainTrait;

class Presensi extends Model
{
    use HasFactory, MainTrait, ModelsCrudTrait;

    protected $table = 'data_presensi';

    protected $fillable = [
        'id_employee',
        'lokasi_absen',
        'checkin',
        'checkout',
        'name',
        'jabatan',
        'hadir',
        'cuti',
        'kuota_cuti',
        'izin',
        'kuota_izin',
        'durasi',
        'durasi_hadir',
        'verifikasi',
        'verifikator',
        'keterangan'
    ];

    protected $casts = [
        'checkin' => 'datetime',
        'checkout' => 'datetime',
        'hadir' => 'integer',
        'cuti' => 'integer',
        'kuota_cuti' => 'integer',
        'izin' => 'integer',
        'kuota_izin' => 'integer',
        'durasi' => 'integer',
        'durasi_hadir' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    public function scopeByMonth($query, $month = null, $year = null)
    {
        if (!$month || !$year) {
            $date = now()->subMonth();
            $month = $date->month;
            $year = $date->year;
        }

        return $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
    }

    public static function rekapByEmployee($month = null, $year = null)
    {
        if (!$month || !$year) {
            $month = date('m');
            $year = date('Y');
        }

        return <<<SQL
            SELECT 
                id_employee,
                name,
                jabatan,
                SUM(hadir) as total_hadir,
                SUM(cuti) as total_cuti,
                SUM(izin) as total_izin,
                MAX(kuota_cuti) as kuota_cuti,
                MAX(kuota_izin) as kuota_izin,
                CASE 
                    WHEN SUM(hadir) >= 22 THEN 'Baik'
                    WHEN SUM(hadir) >= 15 THEN 'Cukup'
                    ELSE 'Kurang'
                END as status_hadir
            FROM data_presensi 
            WHERE EXTRACT(MONTH FROM created_at) = '{$month}'
                AND EXTRACT(YEAR FROM created_at) = '{$year}'
            GROUP BY id_employee, name, jabatan
        SQL;
    }

    public function rules($scenario = 'default')
    {
        $rules = [
            'id_employee' => 'required|exists:data_employees,id',
            'lokasi_absen' => 'required|in:Gedung Utama,Gedung A,Gedung B',
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'verifikasi' => 'nullable|in:disetujui,ditolak',
            'keterangan' => 'nullable|string',
        ];

        return $rules;
    }
}