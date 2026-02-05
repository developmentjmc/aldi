<?php

namespace App\Backend;

use App\Helpers\ExcelTrait;
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
    use ExcelTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('backend.presensi.%');
    }

    public function index(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        
        $sql = Presensi::rekapEmployee($month, $year);
        $query = \App\Models\Presensi::query();
		$query->from(new \Illuminate\Database\Query\Expression("({$sql}) as presensi"));

        $search = new \jeemce\helpers\QuerySearch($query, [
            'search' => $request->get('search'),
            'searchFields' => ['name', 'jabatan'],
            'filter' => $request->get('filter'),
            'sorter' => $request->get('sorter'),
        ]);
        // dd($query->get()->toArray());

        $models = $query->paginate(config('jeemce.pagination.per_page'));
        
        $months = \App\Helpers\DataHelper::getMonth();
        
        $years = range(date('Y') - 2, date('Y'));

        return view("backend/presensi/index", get_defined_vars());
    }

    public function view(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $sql = Presensi::rekapByEmployee($id);
        $query = \App\Models\Presensi::query();
		$query->from(new \Illuminate\Database\Query\Expression("({$sql}) as presensi"));

        $search = new \jeemce\helpers\QuerySearch($query, [
            'search' => $request->get('search'),
            'searchFields' => ['name', 'jabatan'],
            'filter' => $request->get('filter'),
            'sorter' => $request->get('sorter'),
        ]);

        $models = $query->paginate(config('jeemce.pagination.per_page'));
        $years = range(date('Y') - 2, date('Y'));
        $cutiDiambil = Presensi::where('id_employee', $id)->where('cuti', '>', 0)->sum('cuti');

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

    public function exportExcel(Request $request)
    {
        $month = $request->get('month', now()->subMonth()->month);
        $year = $request->get('year', now()->subMonth()->year);

        $data = DBHelper::select(<<<SQL
            SELECT 
                e.id,
                e.name,
                e.jabatan,
                e.kuota_cuti,
                e.kuota_izin
            FROM data_employees e
            ORDER BY e.name
        SQL);

        $filename = "Rekap_Presensi_" . sprintf('%02d', $month) . "_" . $year . ".xlsx";

        $options = [
            'ID' => 'id',
            'Nama' => 'Nama',
            'Jabatan' => 'Jabatan',
            'Checkin' => 'Checkin',
            'Checkout' => 'Checkout',
            'Cuti' => 'Cuti',
            'Kuota Cuti' => 'Kuota Cuti',
            'Izin' => 'Izin',
            'Kuota Izin' => 'Kuota Izin',
            'Lokasi Checkin' => 'Lokasi Checkin',
            'Lokasi Checkout' => 'Lokasi Checkout',
        ];

        $formattedData = array_map(function($item) {
            return [
                'ID' => $item->id,
                'Nama' => $item->name,
                'Jabatan' => $item->jabatan,
                'Checkin' => null,
                'Checkout' => null,
                'Cuti' => null,
                'Kuota Cuti' => $item->kuota_cuti,
                'Izin' => null,
                'Kuota Izin' => $item->kuota_izin,
                'Lokasi Checkin' => null,
                'Lokasi Checkout' => null,
            ];
        }, $data);

        $request->merge(['name' => pathinfo($filename, PATHINFO_FILENAME)]);
        
        return $this->excel($request, $formattedData, $options);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ], [
            'excel_file.required' => 'File harus berformat Excel (.xlsx, .xls)',
            'excel_file.file' => 'File tidak valid',
            'excel_file.mimes' => 'File harus berformat Excel (.xlsx, .xls)',
        ]);

        $file = $request->file('excel_file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->withErrors(['excel_file' => 'File tidak valid']);
        }

        // import data 
        $xlsx = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
		$dataexcel = $xlsx->getActiveSheet()->toArray(null, true, true, true);
        
        foreach ($dataexcel as $key => $value) {
            if ($key == 1 || (empty($value['A']) && empty($value['B']))) {
                continue;
            }
    
            $checkIn = !empty($value['E']) ? date('Y-m-d H:i:s', strtotime($value['E'])) : null;
            $where = [
                'id_employee' => $value['B'],
                'checkin' => !empty($checkIn) ? date('Y-m-d', strtotime($checkIn)) : null,
                'lokasi_checkin' => $value['K'],
                'lokasi_checkout' => $value['L']
            ];

            if (empty($value['K']) || empty($value['L'])) {
                return redirect()->back()->withErrors(['excel_file' => 'Lokasi CheckIn dan Checkout harus diisi pada baris ke-' . $key]);
            }

            if (!empty($value['K']) && !empty($value['L']) && $value['K'] != $value['L']) {
                return redirect()->back()->withErrors(['excel_file' => 'Lokasi CheckIn dan Checkout tidak boleh berbeda, baris ke-' . $key]);
            }

            if (empty($value['B'])) {
                return redirect()->back()->withErrors(['excel_file' => 'ID Pegawai harus diisi pada baris ke-' . $key]);
            }

            if (empty($value['E']) || empty($value['F'])) {
                return redirect()->back()->withErrors(['excel_file' => 'Checkin dan Checkout harus diisi pada baris ke-' . $key]);
            }

            if ($value['G'] > 1 || $value['I'] > 1) {
                return redirect()->back()->withErrors(['excel_file' => 'Kolom cuti dan izin tidak boleh di isi dengan value > 1 pada baris ke-' . $key]);
            }

            Presensi::firstOrNew($where, $value);
        }

        return redirect()->back()->with('success', 'Data presensi berhasil diimport');
    }
    
    public function findModel(array $params)
    {
        $model = Presensi::query()->where($params)->firstOrFail();
        return $model;
    }
}