<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\EnumController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TranslateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 开放路由
|--------------------------------------------------------------------------
*/
Route::get('healthy', [PublicController::class, 'healthy']);
Route::get('server_info', [PublicController::class, 'serverInfo']);
Route::get('test', [PublicController::class, 'test']);

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

/*
|--------------------------------------------------------------------------
| 认证路由
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('password', [AuthController::class, 'changePassword']);
    });

    Route::group(['prefix' => 'enums'], function () {
        Route::get('langs', [EnumController::class, 'langs']);
        Route::get('qualities', [EnumController::class, 'qualities']);
    });

    // 词条
    Route::apiResource('entries', EntryController::class);
    Route::post('entries/{entry}/tags', [EntryController::class, 'storeTags']);
    Route::delete('entries/{entry}/tags/{tag}', [EntryController::class, 'destroyTags']);

    // 翻译
    Route::put('translates/{translate}', [TranslateController::class, 'update']);
    Route::put('translates/{translate}/calibrate', [TranslateController::class, 'calibrate']);
    Route::put('translates/{translate}/publish', [TranslateController::class, 'publish']);
    Route::put('translates/{translate}/revoke', [TranslateController::class, 'revoke']);
});
