<?php

use App\Http\Controllers\OpenapiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 开放路由
|--------------------------------------------------------------------------
*/
Route::post('login', [OpenapiController::class, 'login']);

/*
|--------------------------------------------------------------------------
| 认证路由
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:openapi'], function () {
    Route::get('entries', [OpenapiController::class, 'getEntries']);
    Route::post('anchors', [OpenapiController::class, 'syncAnchors']);
});
