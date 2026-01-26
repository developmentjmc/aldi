<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
	Route::post('register', [RegisteredUserController::class, 'store']);

	Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
	Route::post('login', [AuthenticatedSessionController::class, 'store']);

	Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
	Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

	Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
	Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
	Route::get('otp-input', [AuthenticatedSessionController::class, 'otpInput'])->name('otp.input');
	Route::post('otp-verify', [AuthenticatedSessionController::class, 'otpVerify'])->name('otp.verify');

	Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

	Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
		->middleware(['signed', 'throttle:6,1'])
		->name('verification.verify');

	Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
		->middleware('throttle:6,1')
		->name('verification.send');

	Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
	Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

	Route::put('password', [PasswordController::class, 'update'])->name('password.update');
	Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('auth')->prefix('backend')->name('backend.')->group(function () {
	Route::controller(\App\Backend\HomeController::class)->name('home.')->group(function () {
		Route::get('/', 'index')->name('index');
	});

	Route::controller(\App\Backend\UserController::class)->name('user.')->prefix('user')->group(function () {
		Route::any('change-password', 'changePassword')->name('changePassword');
	});

	Route::controller(\App\Backend\PageController::class)->name('page.')->prefix('page')->group(function () {
		Route::get('{type}', 'index')->name('index');
		Route::get('{type}/form/{slug?}', 'form')->name('form');
		Route::get('{type}/view/{slug?}', 'view')->name('view');
		Route::match(['put', 'post'], '{id?}', 'save')->name('save');
		Route::delete('{id}', 'delete')->name('delete');
	});

	Route::resource('role', \App\Backend\RoleController::class);
	Route::resource('menu', \App\Backend\MenuController::class);
	Route::resource('user', \App\Backend\UserController::class);
	Route::resource('feedback', \App\Backend\FeedbackController::class);

    Route::controller(\App\Backend\SettingController::class)->name('setting.')->prefix('setting')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('test-email', 'sendTest')->name('test.send');
        Route::match(['put', 'post'], '{id?}', 'store')->name('store');
        Route::get('{type}', 'index')->name('types');
        Route::get('{type}/form', 'form')->name('form');
        Route::put('{type}/update', 'update')->name('update');
        Route::delete('{id}', 'destroy')->name('destroy');
    });
});
