<?php

namespace App\Backend;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;
use jeemce\helpers\DBHelper;

class TunjanganTransportController extends Controller
{
    use CrudTrait;
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('backend.pegawai.%');
    }

    public function index(Request $request)
    {
        $baseFare = DataHelper::getBaseFare();
        $sql = <<<SQL
            SELECT tt.*,
                e.name as employee_name,
                e.nip,
                e.jenis_pegawai,
                CASE 
                    WHEN tt.hari_kerja >= 19 
                         AND tt.jarak >= 5 
                         AND tt.jarak <= 25 
                         AND e.jenis_pegawai = 'Tetap'
                    THEN 
                        ROUND({$baseFare} * 
                              CASE 
                                  WHEN tt.jarak - FLOOR(tt.jarak) < 0.5 THEN FLOOR(tt.jarak)
                                  ELSE CEIL(tt.jarak) 
                              END * tt.hari_kerja)
                    ELSE 0
                END as calculated_tunjangan
            FROM tunjangan_transports tt
            JOIN data_employees e ON tt.employee_id = e.id
        SQL;
        $query = \App\Models\TunjanganTransport::query();
        $query->from(new \Illuminate\Database\Query\Expression("({$sql}) as tunjangan_transports"));

        $search = new \jeemce\helpers\QuerySearch($query, [
            'search' => $request->get('search'),
            'searchFields' => ['employee_name', 'nip', 'jenis_pegawai'],
            'filter' => $request->get('filter'),
            'sorter' => $request->get('sorter'),
        ]);
        
        $models = $query->paginate(config('jeemce.pagination.per_page'));
        return view("backend/tunjangan_transport/index", get_defined_vars());
    }

    public function view($id)
    {
        $model = $this->findModel(['id' => $id]);
        $this->validateAccess('view', $model, 'id');

        return view('backend/tunjangan_transport/view', get_defined_vars());
    }

    /**
     * MERGE create|edit
     */
    public function form($id = null)
    {
        if ($id) {
            $model = $this->findModel(['id' => $id]);
            $this->validateAccess('update', $model);
        } else {
            $model = new \App\Models\TunjanganTransport;
        }
        $baseFare = DataHelper::getBaseFare();
        $employees = DBHelper::select("SELECT * FROM data_employees WHERE jenis_pegawai = 'Tetap' ORDER BY name");
        $gedung = DBHelper::select("SELECT name, description FROM data_masters WHERE name in ('Gedung Utama', 'Gedung A', 'Gedung B') ORDER BY name");
        return view('backend/tunjangan_transport/form', get_defined_vars());
    }

    /**
     * MERGE store|update
     */
    public function save(Request $request, $id = null)
    {
        if ($id) {
            $model = $this->findModel(['id' => $id]);
        } else {
            $model = new \App\Models\TunjanganTransport;
        }

        $params = $request->all();
        $model->validator($params)->validate();
        
        if ($request->ajax()) {
            return;
        }

        $model->autoFill($params);
        
        $model->calculateTunjangan();
        
        $model->saveOrFail();

        return redirect()->action([static::class, 'index'])->with('saveDone', 'Proses Simpan Berhasil');
    }

    /**
     * @return \App\models\TunjanganTransport
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findModel(array $params)
    {
        $model = \App\Models\TunjanganTransport::query()->where($params)->firstOrFail();
        return $model;
    }
}