<?php

namespace App\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use jeemce\helpers\QuerySearch;
use jeemce\models\Access;
use jeemce\models\Role;

class RoleController extends Controller
{
	use \jeemce\controllers\CrudTrait;
	use \jeemce\controllers\AuthTrait;

	public function __construct()
	{
		$this->middlewareResourceAccess('backend.role.%');
	}

	public function index(Request $request)
	{
		$query = Role::query();

		$search = new QuerySearch($query, [
			'search' => $request->get('search'),
			'sorter' => $request->get('sorter'),
		]);

		$models = $query->paginate(10);

		return view("backend.role.index", get_defined_vars());
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
			$model = new \jeemce\models\Role;
		}

		$accessByRole = Access::byRole($id);
		$accessTypeOptions = Access::typeOptions();

		$menuTypes = array_filter(array_keys(Menu::typeOptions()), fn ($type) => strpos($type, 'backend_') === 0);
		$treeModels = Menu::tree(['id_menu' => null, 'type' => $menuTypes]);

		return view('backend/role/form', get_defined_vars());
	}

	/**
	 * MERGE store|update
	 */
	public function save(Request $request, $id = null)
	{
		if (empty($id)) {
			$model = new Role;
		} else {
			$model = $this->findModel(['id' => $id]);
		}

		$params = $request->all();
		$model->validator($params)->validate();
		if ($request->ajax()) {
			return;
		}
		$model->autoFill($params);
		$model->saveOrFail();

		$params['access'] ??= [];
		foreach ($params['access'] as $id_menu => $entry) {
			$accessModel = Access::firstOrNew([
				'id_role' => $model->id,
				'id_menu' => $id_menu,
			]);
			$accessModel->autoFill($entry);
			$accessModel->saveOrFail();
		}

		return redirect()->action([static::class, 'index'])->with('saveDone', 'Proses Simpan Berhasil');
	}

	/**
	 * @return Role
	 */
	public function findModel(array $params)
	{
		$model = Role::query()->where($params)->firstOrFail();
		return $model;
	}
}
