<?php

use App\Backend\EmployeeController;
use App\Backend\LogController;
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
    Route::resource('/pegawai', EmployeeController::class, ['as' => 'backend']);
    Route::resource('/tunjangan-transport', TunjanganTransportController::class, ['as' => 'backend']);
    Route::resource('/log', LogController::class, ['as' => 'backend'])->only(['index', 'show']);

    Route::resource('/presensi', PresensiController::class, ['as' => 'backend']);
    Route::get('/presensi/export-excel', [PresensiController::class, 'exportExcel'])->name('backend.presensi.export');
});

require __DIR__.'/auth.php';
