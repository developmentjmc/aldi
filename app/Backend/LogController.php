<?php

namespace App\Backend;

use App\Models\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;

class LogController extends Controller
{
    use CrudTrait;
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('backend.log.%');
    }

    public function index(Request $request)
    {
        $sql = <<<SQL
            SELECT logs.id,
                logs.tanggal,
                logs.jam,
                logs.username,
                logs.deskripsi,
                logs.modul,
                logs.aksi,
                logs.created_at
            FROM logs
            ORDER BY logs.tanggal DESC, logs.jam DESC
        SQL;
        $query = Log::query();
        $query->from(new \Illuminate\Database\Query\Expression("({$sql}) as logs"));

        $search = new \jeemce\helpers\QuerySearch($query, [
            'search' => $request->get('search'),
            'searchFields' => ['username', 'deskripsi', 'modul', 'aksi'],
            'filter' => $request->get('filter'),
            'sorter' => $request->get('sorter'),
        ]);

        $models = $query->paginate(config('jeemce.pagination.per_page'));
        return view("backend/log/index", get_defined_vars());
    }

    public function view($id)
    {
        $model = $this->findModel(['id' => $id]);
        $this->validateAccess('view', $model, 'id');

        return view('backend/log/view', get_defined_vars());
    }

    /**
     * @return Log
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findModel(array $params)
    {
        $model = Log::query()->where($params)->firstOrFail();
        return $model;
    }
}