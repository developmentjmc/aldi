<?php

namespace App\Backend;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\DataMaster;
use App\Models\Log;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;

class MasterController extends Controller
{
    use CrudTrait;
	use AuthTrait;

    public function __construct()
	{
		$this->middlewareResourceAccess('backend.master.%');
	}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		$sql = <<<SQL
			SELECT * 
			FROM data_masters
			WHERE tipe in ('base fare', 'master')
		SQL;
		$query = DataMaster::query();
		$query->from(new Expression("({$sql}) as data_masters"));

		$search = new \jeemce\helpers\QuerySearch($query, [
			'search' => $request->get('search'),
			'searchFields' => ['name', 'description'],
			'filter' => $request->get('filter'),
			'sorter' => $request->get('sorter'),
		]);

		$models = $query->paginate(config('jeemce.pagination.per_page'));
		return view("backend/master/index", get_defined_vars());
    }

	public function form($id = null)
	{
		if ($id) {
			$model = $this->findModel(['id' => $id]);
			$this->validateAccess('update', $model);
		} else {
			$model = new \App\Models\DataMaster;
		}
		return view('backend/master/form', get_defined_vars());
	}

	/**
	 * MERGE store|update
	 */
	public function save(Request $request, $id = null)
	{
		if ($id) {
			$model = $this->findModel(['id' => $id]);
			$aksi = 'update';
			$deskripsi = "Mengupdate data master: {$model->name} (ID: {$model->id})";
		} else {
			$model = new \App\Models\DataMaster;
			$aksi = 'create';
		}

		$params = $request->all();
		$model->validator($params)->validate();
		if ($request->ajax()) {
			return;
		}
		$model->autoFill($params);
		$model->saveOrFail();

		if ($aksi == 'create') {
			$deskripsi = "Menambah data master baru: {$model->name} (ID: {$model->id})";
		}
		Log::record($deskripsi, 'Master', $aksi);

		return redirect()->action([static::class, 'index'])->with('saveDone', 'Proses Simpan Berhasil');
	}

	/**
	 * @return \App\models\DataMaster
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function findModel(array $params)
	{
		$model = \App\Models\DataMaster::query()->where($params)->firstOrFail();
		return $model;
	}

	public function destroy(Request $request, $id)
	{
		return redirect()->action([static::class, 'index']);
	}
}
