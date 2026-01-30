<?php

namespace App\Models;

use Illuminate\Container\Attributes\DB;
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
        'keterangan',
        'status_hadir'
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $userLogin = auth()->user()->name;
		    Log::record("{$userLogin } Menambah data presensi", 'Presensi', 'create');
        });

        static::updating(function ($model) {
            $userLogin = auth()->user()->name;
            Log::record("{$userLogin} Mengupdate data presensi ID: {$model->id}", 'Presensi', 'update');
        });
    }

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

    public static function rekapEmployee($month = null, $year = null)
    {
        if (!$month || !$year) {
            $month = date('m');
            $year = date('Y');
        }

        return <<<SQL
            SELECT 
                data_presensi.id_employee,
                data_presensi.name,
                data_presensi.jabatan,
                SUM(data_presensi.durasi_hadir) AS durasi_hadir,
                SUM(data_presensi.cuti) AS cuti,
                data_employees.kuota_cuti,
                SUM(data_presensi.izin) AS izin,
                data_employees.kuota_izin
            FROM data_presensi
            left JOIN data_employees ON data_presensi.id_employee = data_employees.id
            WHERE EXTRACT(MONTH FROM data_presensi.checkin) = {$month}
                AND EXTRACT(YEAR FROM data_presensi.checkin) = {$year}
            GROUP BY data_presensi.id_employee, data_presensi.name, data_presensi.jabatan, data_employees.kuota_cuti, data_employees.kuota_izin
        SQL;
    }

    public static function rekapByEmployee($id = null)
    {
        return <<<SQL
            SELECT 
                data_presensi.id,
                data_presensi.id_employee,
                data_presensi.name,
                data_presensi.jabatan,
                data_presensi.durasi_hadir,
                data_presensi.status_hadir,
                data_presensi.cuti,
                data_employees.kuota_cuti,
                data_presensi.izin,
                data_employees.kuota_izin,
                data_presensi.checkin,
                data_presensi.checkout,
                data_presensi.lokasi_absen
            FROM data_presensi
            left JOIN data_employees ON data_presensi.id_employee = data_employees.id 
            WHERE data_presensi.id_employee = {$id}
        SQL;
    }

    public static function firstOrNew($where, $value)
    {
        $checkIn = !empty($value['E']) ? date('Y-m-d H:i:s', strtotime($value['E'])) : null;
        $checkOut = !empty($value['F']) ? date('Y-m-d H:i:s', strtotime($value['F'])) : null;

        $check = DBHelper::selectOne(<<<SQL
            SELECT *
            FROM data_presensi
            WHERE id_employee = :id_employee
                AND DATE(checkin) = :checkin
                AND lokasi_absen = :lokasi_absen
            LIMIT 1
        SQL, [
            'id_employee' => $where['id_employee'],
            'checkin' => $where['checkin'],
            'lokasi_absen' => $where['lokasi_absen'],
        ]);

        $checkIn = !empty($value['E']) ? date('Y-m-d H:i:s', strtotime($value['E'])) : null;
        $checkOut = !empty($value['F']) ? date('Y-m-d H:i:s', strtotime($value['F'])) : null;

        if ($value['G'] > 0 || $value['I'] > 0) {
            $checkIn = date('Y-m-d').' 08:00:00';
            $checkOut = date('Y-m-d').' 08:00:00';
        }

        $statusHadir = 'Tidak Hadir';
        $duration = 0;
        $keterangan = $value['L'] ?? null;

        // kudu min 8 jam kerja
        if (!empty($checkIn) && !empty($checkOut)) {
            $duration = (strtotime($checkOut) - strtotime($checkIn)) / 3600;
            if ($duration >= 8) {
                $statusHadir = 'Hadir';
            }else{
                $statusHadir = 'Tidak Terpenuhi';
            }
        }

        // max mangkat jam 8, jika telat > 15 menit dianggap tidak hadir
        if (!empty($checkIn)) {
            $startOfDay = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($checkIn)) . ' 08:00:00'));
            $lateMinutes = (strtotime($checkIn) - strtotime($startOfDay)) / 60;
            if ($lateMinutes > 15) {
                $statusHadir = 'Tidak Hadir';
                $keterangan .= ' - (Terlambat lebih dari 15 menit)';
            }
        }

        $duration = number_format($duration, 2);
        $data = [
            'id_employee' => $value['B'],
            'checkin' => $checkIn,
            'checkout' => $checkOut,
            'lokasi_absen' => $value['K'],
            'name' => $value['C'] ?? null,
            'jabatan' => $value['D'] ?? null,
            'status_hadir' => $statusHadir,
            'cuti' => (int)($value['G'] ?? 0),
            'kuota_cuti' => (int)($value['H'] ?? 0),
            'izin' => (int)($value['I'] ?? 0),
            'kuota_izin' => (int)($value['J'] ?? 0),
            'durasi_hadir' => $duration,
            'keterangan' => $keterangan,
        ];

        if (!$check) {
            DBHelper::insert(<<<SQL
                INSERT INTO data_presensi 
                (id_employee, checkin, checkout, lokasi_absen, name, jabatan, 
                status_hadir, cuti, kuota_cuti, izin, kuota_izin, durasi_hadir, 
                keterangan, created_at, updated_at)
                VALUES 
                (:id_employee, :checkin, :checkout, :lokasi_absen, :name, :jabatan, 
                :status_hadir, :cuti, :kuota_cuti, :izin, :kuota_izin, :durasi_hadir, 
                :keterangan, :created_at, :updated_at)
            SQL, array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }else {
            DBHelper::update(<<<SQL
                UPDATE data_presensi 
                SET id_employee = :id_employee,
                    checkin = :checkin,
                    checkout = :checkout,
                    name = :name,
                    jabatan = :jabatan,
                    status_hadir = :status_hadir,
                    cuti = :cuti,
                    kuota_cuti = :kuota_cuti,
                    izin = :izin,
                    kuota_izin = :kuota_izin,
                    durasi_hadir = :durasi_hadir,
                    keterangan = :keterangan,
                    updated_at = :updated_at,
                    lokasi_absen = :lokasi_absen
                WHERE id = :id
            SQL, array_merge($data, [
                'updated_at' => now(),
                'id' => $check->id,
            ]));
        }
        return $check;
    }
}