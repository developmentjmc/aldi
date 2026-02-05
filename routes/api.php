<?php

use App\Backend\JsonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wilayah', [JsonController::class, 'wilayah']);
    Route::get('/test', [JsonController::class, 'test']);
});

Route::post('/login', [JsonController::class, 'login']);

