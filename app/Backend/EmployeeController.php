<?php

namespace App\Backend;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;

class EmployeeController extends Controller
{
    use CrudTrait;
	use AuthTrait;

    public function __construct()
	{
		$this->middlewareResourceAccess('backend.pegawai.%');
	}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		$sql = <<<SQL
			SELECT employees.id,
				employees.nip,
				employees.name,
				employees.jabatan,
				employees.jenis_pegawai,
				employees.tanggal_masuk,
				EXTRACT(YEAR FROM AGE(CURRENT_DATE, employees.tanggal_masuk)) AS masa_kerja
			FROM data_employees AS employees
		SQL;
		$query = \App\Models\Employee::query();
		$query->from(new \Illuminate\Database\Query\Expression("({$sql}) as employees"));

		$search = new \jeemce\helpers\QuerySearch($query, [
			'search' => $request->get('search'),
			'searchFields' => ['name', 'nip', 'jabatan', 'jenis_pegawai'],
			'filter' => $request->get('filter'),
			'sorter' => $request->get('sorter'),
		]);

		$models = $query->paginate(config('jeemce.pagination.per_page'));
		return view("backend/employee/index", get_defined_vars());
    }

	public function form($id = null)
	{
		if ($id) {
			$model = $this->findModel(['id' => $id]);
			$this->validateAccess('update', $model);
		} else {
			$model = new \App\Models\Employee;
		}
		return view('backend/employee/form', get_defined_vars());
	}

	/**
	 * MERGE store|update
	 */
	public function save(Request $request, $id = null)
	{
		if ($id) {
			$model = $this->findModel(['id' => $id]);
			$aksi = 'update';
			$deskripsi = "Mengupdate data pegawai: {$model->name} (NIP: {$model->nip})";
		} else {
			$model = new \App\Models\Employee;
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
			$deskripsi = "Menambah data pegawai baru: {$model->name} (NIP: {$model->nip})";
		}
		Log::record($deskripsi, 'Pegawai', $aksi);

		return redirect()->action([static::class, 'index'])->with('saveDone', 'Proses Simpan Berhasil');
	}

	/**
	 * @return \App\models\Employee
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function findModel(array $params)
	{
		$model = \App\Models\Employee::query()->where($params)->firstOrFail();
		return $model;
	}

	public function destroy(Request $request, $id)
	{
		$model = $this->findModel(['id' => $id]);
		$this->validateAccess('delete', $model);
		
		$name = $model->name;
		$nip = $model->nip;
		
		$model->delete();
		
		// Log aktivitas delete
		Log::record("Menghapus data pegawai: {$name} (NIP: {$nip})", 'Pegawai', 'delete');

		$redirectUrl = $request->get('redirect') ?: route('backend.pegawai.index');
		return redirect($redirectUrl)->with('deleteDone', 'Data berhasil dihapus');
	}
}
