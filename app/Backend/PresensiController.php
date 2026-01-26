<?php

namespace App\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Employee;
use jeemce\helpers\AuthHelper;
use jeemce\helpers\DBHelper;

class PresensiController extends Controller
{
    use \jeemce\controllers\CrudTrait;
    use \jeemce\controllers\AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('backend.presensi.%');
    }

    public function index(Request $request)
    {
        $month = $request->get('month', now()->subMonth()->month);
        $year = $request->get('year', now()->subMonth()->year);
        
        $sql = Presensi::rekapByEmployee($month, $year);
        $query = \App\Models\Presensi::query();
		$query->from(new \Illuminate\Database\Query\Expression("({$sql}) as presensi"));

        $search = new \jeemce\helpers\QuerySearch($query, [
            'search' => $request->get('search'),
            'searchFields' => ['name', 'jabatan'],
            'filter' => $request->get('filter'),
            'sorter' => $request->get('sorter'),
        ]);

        $models = $query->paginate(config('jeemce.pagination.per_page'));
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $years = range(date('Y') - 2, date('Y'));

        return view("backend/presensi/index", get_defined_vars());
    }

    public function view($id)
    {
        $employee = Employee::findOrFail($id);
        
        $month = request()->get('month', now()->subMonth()->month);
        $year = request()->get('year', now()->subMonth()->year);
        
        $presensiData = Presensi::where('id_employee', $id)
                               ->byMonth($month, $year)
                               ->orderBy('created_at', 'desc')
                               ->paginate(config('jeemce.pagination.per_page'));
                               
        $rekap = Presensi::where('id_employee', $id)
                         ->byMonth($month, $year)
                         ->selectRaw('
                            SUM(hadir) as total_hadir,
                            SUM(cuti) as total_cuti, 
                            SUM(izin) as total_izin,
                            MAX(kuota_cuti) as kuota_cuti,
                            MAX(kuota_izin) as kuota_izin,
                            AVG(durasi_hadir) as avg_durasi_hadir
                         ')
                         ->first();

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $years = range(date('Y') - 2, date('Y'));

        return view('backend/presensi/view', get_defined_vars());
    }

    public function form($id = null)
    {
        if ($id) {
            $model = $this->findModel(['id' => $id]);
            $this->validateAccess('update', $model);
        } else {
            $model = new Presensi;
        }

        $employees = Employee::orderBy('name')->get();
        $lokasiAbsen = ['Gedung Utama', 'Gedung A', 'Gedung B'];

        return view('backend/presensi/form', get_defined_vars());
    }


    /**
     * @todo, nanti akan sy buat import
     */
    public function save(Request $request, $id = null)
    {
       exit('Fungsi Simpan belum tersedia, akan segera dibuatkan pada update berikutnya.');
    }

    public function exportExcel(Request $request)
    {
        $month = $request->get('month', now()->subMonth()->month);
        $year = $request->get('year', now()->subMonth()->year);

        // Data untuk export
        $query = Presensi::rekapByEmployee($month, $year);
        $data = DBHelper::select($query);

        $headers = [
            'Nama',
            'Jabatan', 
            'Hadir',
            'Status Hadir',
            'Cuti',
            'Kuota Cuti',
            'Izin',
            'Kuota Izin'
        ];

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                $item->name,
                $item->jabatan,
                $item->total_hadir,
                $item->status_hadir,
                $item->total_cuti,
                $item->kuota_cuti,
                $item->total_izin,
                $item->kuota_izin
            ];
        }

        $filename = "Rekap_Presensi_" . sprintf('%02d', $month) . "_" . $year . ".xlsx";

        echo "<pre>";
        print($headers);
        echo "<pre>";
        print_r($rows);

        // return (new \jeemce\controllers\ExcelExportV1Controller)->export([
        //     'headers' => $headers,
        //     'data' => $rows,
        //     'filename' => $filename,
        //     'title' => "Rekap Presensi Bulan " . $month . " Tahun " . $year
        // ]);
    }
    
    public function findModel(array $params)
    {
        $model = Presensi::query()->where($params)->firstOrFail();
        return $model;
    }
}