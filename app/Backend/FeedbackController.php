<?php

namespace App\Backend;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
	use \jeemce\controllers\AuthTrait;
	use \jeemce\controllers\CrudTrait;

	// public function __construct()
	// {
	// 	$this->middlewareResourceAccess('backend.feedback.%');
	// }

	public function index(Request $request)
	{
		$query = Feedback::query();
		$search = new \jeemce\helpers\QuerySearch($query, [
			'search' => $request->get('search'),
			'filter' => $request->get('filter'),
			'sorter' => $request->get('sorter'),
			'sorterDefaultFields' => ['created_at' => 'desc'],
		]);

		$models = $query->paginate(config('jeemce.pagination.per_page'));

		return view("backend.feedback.index", get_defined_vars());
	}

	/**
	 * MERGE create|edit
	 */
	public function form($id = null)
	{
		if (empty($id)) {
			$model = new Feedback;
		} else {
			$model = $this->findModel(['id' => $id]);
		}

		return view('backend/feedback/form', get_defined_vars());
	}

	/**
	 * MERGE store|update
	 */
	public function save(Request $request, $id = null)
	{
		if (empty($id)) {
			$model = new Feedback;
		} else {
			$model = $this->findModel(['id' => $id]);
		}

		$params = $request->all();
		$validator = $model->validator($params);
		$validator->validate();

		if ($request->ajax()) {
			return;
		}

		$model->autoFill($params);
		$model->saveOrFail();

		return redirect()->action([static::class, 'index'])->with('saveDone', 'Proses Simpan Berhasil');
	}

	/**
	 * @return Feedback
	 */
	public function findModel(array $params)
	{
		return Feedback::where($params)->firstOrFail();
	}
}
