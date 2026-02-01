<?php

namespace App\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use jeemce\helpers\AuthHelper;
use jeemce\helpers\DBHelper;

class UserController extends Controller
{
	use \jeemce\controllers\CrudTrait;
	use \jeemce\controllers\AuthTrait;

	public function __construct()
	{
		$this->middlewareResourceAccess('backend.user.%');
	}

	public function index(Request $request)
	{
		$whereRole = '';
		// if (Auth::user()->role->name != 'Superadmin') {
		// 	$idLoggedUser = Auth::id();
		// 	$whereRole = "AND users.created_by = {$idLoggedUser}";
		// }
		$sql = <<<SQL
			SELECT users.*, roles.name AS role_name
			FROM users
			LEFT JOIN roles ON roles.id = users.id_role
			WHERE 1=1 {$whereRole}
		SQL;

		// biar tetep bisa pakai preset
		$query = \App\Models\User::query();
		$query->from(new \Illuminate\Database\Query\Expression("({$sql}) as users"));

		$search = new \jeemce\helpers\QuerySearch($query, [
			'search' => $request->get('search'),
			'searchFields' => ['name', 'email', 'phone', 'username', 'role_name'],
			'filter' => $request->get('filter'),
			'sorter' => $request->get('sorter'),
			'defaultSorter' => ['id' => 'DESC'],
		]);

		$models = $query->paginate(config('jeemce.pagination.per_page'));

		return view("backend/user/index", get_defined_vars());
	}

	public function view($id)
	{
		$model = $this->findModel(['id' => $id]);
		$this->validateAccess('view', $model, 'id');

		return view('backend/user/view', get_defined_vars());
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
			$model = new \App\Models\User;
		}

		$employees = \App\Helpers\DataHelper::getEmployee()
		->orderBy('name')
		->get()
		->keyBy('id');

		return view('backend/user/form', get_defined_vars());
	}

	/**
	 * MERGE store|update
	 */
	public function save(Request $request, $id = null)
	{
		if ($id) {
			$model = $this->findModel(['id' => $id]);
		} else {
			$model = new \App\Models\User;
		}

		$params = $request->all();
		$model->validator($params)->validate();
		if ($request->ajax()) {
			return;
		}
		if ($id && (empty($params['password']) || is_null($params['password']))) {
			unset($params['password']);
			unset($params['password_confirmation']);
		}
		$model->autoFill($params);
		$model->saveOrFail();

		return redirect()->action([static::class, 'index'])->with('saveDone', 'Proses Simpan Berhasil');
	}

	public function changePassword(Request $request)
	{
		/** @var \App\models\User */
		$model = AuthHelper::user();

		if ($request->isMethod('POST')) {
			$params = $request->all();

			$model->validator($params, $model->rules('change_password'))->validate();
			if ($request->ajax()) {
				return;
			}

			$model->setAttribute('password', $params['password_new']);
			$model->saveOrFail();

			return redirect()->back()->with('success', 'Proses Simpan Berhasil');
		}

		return view('backend/user/change_password', get_defined_vars());
	}

	/**
	 * @return \App\models\User
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function findModel(array $params)
	{
		$model = \App\Models\User::query()->where($params)->firstOrFail();
		return $model;
	}
}
