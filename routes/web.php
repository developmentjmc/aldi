<?php

use App\Backend\EmployeeController;
use App\Backend\JsonController;
use App\Backend\LogController;
use App\Backend\MasterController;
use App\Backend\PresensiController;
use App\Backend\TunjanganTransportController;
use App\Backend\SiteController;
use App\Http\Middleware\AuthWithOtp;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([AuthWithOtp::class])->prefix('backend')->group(function () {
    Route::get('/dashboard', [SiteController::class, 'index'])->name('backend.dashboard');
    
    Route::get('/pegawai/pdf', [EmployeeController::class, 'pdf'])->name('backend.pegawai.pdf');
    Route::resource('/pegawai', EmployeeController::class, ['as' => 'backend']);

    Route::resource('/tunjangan-transport', TunjanganTransportController::class, ['as' => 'backend']);
    Route::resource('/log', LogController::class, ['as' => 'backend'])->only(['index', 'show']);

    Route::get('/presensi/export-excel', [PresensiController::class, 'exportExcel'])->name('backend.presensi.export');
    Route::post('/presensi/import-excel', [PresensiController::class, 'importExcel'])->name('backend.presensi.import');
    Route::get('/presensi/view/{presensi}', [PresensiController::class, 'view'])->name('backend.presensi.view');
    Route::resource('/presensi', PresensiController::class, ['as' => 'backend']);

    Route::resource('/master', MasterController::class, ['as' => 'backend']);

    Route::get('/json/wilayah', [JsonController::class, 'wilayah'])->name('backend.json.wilayah');
});

require __DIR__.'/auth.php';
