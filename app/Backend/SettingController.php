<?php

namespace App\Backend;

use App\Models\Settings;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;
use jeemce\controllers\CrudTrait;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
  use AuthTrait;
  use CrudTrait;

  public function __construct()
  {
    // $this->middlewareResourceAccess('backend.setting.%');
  }

  public function index($setType = 'contact')
  {
    if (Settings::count() < 0) return redirect()->action([static::class, 'initial']);
    return redirect()->action([static::class, 'form'], ['type' => $setType]);
  }
  public function save(Request $request, $id = null)
  {

    if ($id) {
      $model = $this->findModel(['id' => $id]);
      $this->validateAccess('update', $model);
    } else {
      $model = new Settings;
      $this->validateAccess('create', $model);
    }

    $params = $request->all();
    $model->validator($params)->validate();
    if ($request->ajax()) {
      return;
    }
    $model->autoFill($params);
    $model->saveOrFail();

    return redirect()->action([static::class, 'index'], ['type' => $model->type])->with('saveDone', 'Proses Simpan Berhasil');
  }

  public function form($setType = null)
  {
    $model = new Settings;
    $this->validateAccess('update', $model);
    $exists = $model->where('type', $setType)->exists();
    abort_if(!$exists, 404);

    $type = $model->pluck('type')->unique()->toArray();
    $models = $model->where('type', $setType)->orderBy('sort', 'asc')->get();
    return view('backend/setting/form', get_defined_vars());
  }

  public function update(Request $request, $type = null)
  {
    $model = Settings::where('type', $type);
    $exists = $model->exists();

    abort_if(!$exists, 404);
    if ($request->ajax()) return;
    $this->validateAccess('update', $model);

    if ($request->isMethod('PUT')) {
      $rules = $model->pluck('key')->mapWithKeys(function ($key) {
        $arr = [];
        return [$key => $arr];
      })->toArray();
      $params = $request->all();
      (new Settings)->validator($params, $rules)->validate();

      $paramsExec = $request->except(['_token', '_method']);
      foreach ($paramsExec as $key => $val) {
        $data = [
          'type' => $type,
          'key' => $key,
        ];
        Settings::updateOrCreate($data, [
          'val' => $val
        ]);
      }
      return redirect()->action([static::class, 'form'],  ['type' => $type]);
    }

    return redirect()->back();
  }

  /**
   * @return Settings
   */
  public function findModel(array $params)
  {
    $model = Settings::query()->where($params)->firstOrFail();
    return $model;
  }

  public function sendTest(Request $request)
  {
    $logId = uniqid('email_');

    $model = new Settings;
    $exists = $model->where('type', 'smtp')->exists();
    abort_if(!$exists, 404);

    $rules = [
      'email' => ['required', 'email'],
      'subject' => ['required'],
      'content' => ['required'],
    ];
    $params = $request->all();

    (new Settings)->validator($params, $rules)->validate();

    $smtp = Settings::where('type', 'smtp')
      ->whereIn('key', [
        'mail-mailer',
        'mailer-host',
        'mailer-port',
        'mailer-username',
        'mailer-password',
        'mailer-email',
        'mailer-from',
        'mailer-encryption'
      ])
      ->pluck('val', 'key')
      ->toArray();

    $driver       = strtolower($smtp['mail-mailer'] ?? 'smtp');
    $host         = $smtp['mailer-host'] ?? null;
    $port         = $smtp['mailer-port'] ?? 465;
    $username     = $smtp['mailer-username'] ?? null;
    $password     = $smtp['mailer-password'] ?? null;
    $fromEmail    = $smtp['mailer-email'] ?? $username;
    $fromName     = $smtp['mailer-from'] ?? 'System Mailer';
    $encryption   = strtolower($smtp['mailer-encryption'] ?? 'ssl');


    config([
      'mail.default' => $driver,
      "mail.mailers.$driver.transport" => $driver,
      "mail.mailers.$driver.host" => $host,
      "mail.mailers.$driver.port" => $port,
      "mail.mailers.$driver.username" => $username,
      "mail.mailers.$driver.password" => $password,
      "mail.mailers.$driver.encryption" => $encryption,
      'mail.from.address' => $fromEmail,
      'mail.from.name' => $fromName,
    ]);

    try {
      app('mail.manager')->setDefaultDriver($driver);
      app('mail.manager')->mailer($driver)->getSymfonyTransport();


      Mail::raw($request->content, function ($message) use ($request, $fromEmail, $fromName, $logId) {
        $message->from($fromEmail, $fromName)
          ->to($request->email)
          ->subject($request->subject);
      });


      return redirect()->back()->with('success', 'Email berhasil dikirim.');
    } catch (\Throwable $e) {
      return redirect()->back()->with('error', 'Gagal kirim email: ' . $e->getMessage());
    }
  }
}
