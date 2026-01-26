<?php

namespace App\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use jeemce\helpers\QuerySearch;

class PageController extends Controller
{
	use \jeemce\controllers\CrudTrait;

	public function index(Request $request, string $type)
	{
		$query = Page::query()->where(['type' => $type]);

		$search = new QuerySearch($query, [
			'search' => $request->get('search'),
			'filter' => $request->get('filter'),
			'sorter' => $request->get('sorter'),
			'sorterDefaultFields' => [
				'updated_at' => 'desc',
				'created_at' => 'desc',
			],
		]);

		$models = $query->paginate(10);

		return View::first([
			"backend/page/{$type}_index",
			'backend/page/index',
		], get_defined_vars());
	}

	/**
	 * MERGE create|edit
	 */
	public function form(Request $request, string $type, ?string $slug = null)
	{
		$model = $this->findModel(['type' => $type, 'slug' => $slug]);
		$model->metaModel('seo')->mergeCasts(['val' => 'array']);

		return View::first([
			"backend/page/{$type}_form",
			'backend/page/form',
		], get_defined_vars());
	}

	/**
	 * MERGE store|update
	 */
	public function save(Request $request, $id = null)
	{
		$model = $this->findModel(['id' => $id]);
		$model->metaModel('seo')->mergeCasts(['val' => 'array']);

		$params = $request->all();
		$rules = $model->rules($params['type']);
		$model->validator($params, $rules)->validate();
		if ($request->ajax()) {
			return;
		}

		$model->autoFill($params, ['meta']);
		$model->saveOrFail();


		$redirect = $request->get('redirect');
		if ($redirect) {
			return redirect($redirect);
		}

		return redirect()->action([static::class, 'index'],  ['type' => $model->type])->with('saveDone', 'Proses Simpan Berhasil');
	}

	/**
	 * @return Page
	 */
	public function findModel(array $params)
	{
		$model = Page::firstOrNew($params);
		return $model;
	}
}
