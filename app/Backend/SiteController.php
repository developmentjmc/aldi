<?php

namespace App\Backend;

use App\Models\Employee;
use Illuminate\Container\Attributes\DB;
use jeemce\helpers\DBHelper;

class SiteController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $totalPegawai = Employee::count();
        $totalPegawaiKontrak = Employee::where('jenis_pegawai', 'Kontrak')->count();
        $totalPegawaiTetap = Employee::where('jenis_pegawai', 'Tetap')->count();
        $totalPesertaMagang = Employee::where('jenis_pegawai', 'Magang')->count();
        // $pegawaiTerbaru = Employee::orderBy('tanggal_masuk', 'desc')->limit(5)->get();
        $pegawaiTerbaru = DBHelper::select(<<<SQL
            SELECT id, name, nip, jenis_pegawai, tanggal_masuk
            FROM data_employees
            ORDER BY tanggal_masuk DESC
            LIMIT 5
        SQL);
        $pegawaiDomisili = Employee::selectRaw('name, alamat_detail alamat, latitude, longitude, jenis_pegawai')->get();
        return view('backend.dashboard.index', get_defined_vars());
    }
}