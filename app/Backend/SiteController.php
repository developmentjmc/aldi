<?php

namespace App\Backend;

use App\Models\Employee;

class SiteController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $totalPegawai = Employee::count();
        $totalPegawaiKontrak = Employee::where('jenis_pegawai', 'Kontrak')->count();
        $totalPegawaiTetap = Employee::where('jenis_pegawai', 'Tetap')->count();
        $totalPesertaMagang = Employee::where('jenis_pegawai', 'Magang')->count();
        $pegawaiTerbaru = Employee::orderBy('created_at', 'desc')->limit(5)->get();
        $pegawaiDomisili = Employee::selectRaw('name, alamat_detail alamat, latitude, longitude, jenis_pegawai')->get();
        return view('backend.dashboard.index', get_defined_vars());
    }
}