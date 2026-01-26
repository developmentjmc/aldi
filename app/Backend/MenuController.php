<?php

namespace App\Backend;

use App\Models\Menu;
use Illuminate\Http\Request;
use jeemce\helpers\QuerySearch;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
  use CrudTrait, AuthTrait;

  public function __construct()
  {
    $this->middlewareResourceAccess('backend.menu.%');
  }

  public function index(Request $request)
  {
    $query = Menu::query();

    $search = new QuerySearch($query, [
      'filter' => $request->get('filter'),
      'search' => $request->get('search'),
    ]);

    $models = Menu::tree([
      'id_menu' => null,
      function ($query) use ($search) {
        if ($search->filterValue('type')) {
          $query->where('type', '=', $search->filterValue('type'));
        }
        if ($search->filterValue('status')) {
          $query->where('status', '=', $search->filterValue('status'));
        }
      }
    ]);

    return view("backend.menu.index", get_defined_vars());
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
      $model = new Menu;
    }

    return view('backend/menu/form', get_defined_vars());
  }

  /**
   * MERGE store|update
   */
  public function save(Request $request, $id = null)
  {
    if (empty($id)) {
      $model = new Menu;
    } else {
      $model = $this->findModel(['id' => $id]);
    }

    $params = $request->all();
    $route_params = $params['route_params'] ?? [];
    $params['route_params'] = array_combine(
      $route_params['key'] ?? [],
      $route_params['val'] ?? [],
    );

    $model->validator($params)->validate();
    if ($request->ajax()) {
      return;
    }

    $model->autoFill($params);
    $model->saveOrFail();

    return redirect()->action([static::class, 'index'])->with('saveDone', 'Proses Simpan Berhasil');
  }

  /**
   * @return Menu
   */
  public function findModel(array $params)
  {
    $model = Menu::query()->where($params)->firstOrFail();
    return $model;
  }
}
