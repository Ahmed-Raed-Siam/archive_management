<?php

use App\Http\Controllers\API\AuthTokensController;
use App\Http\Controllers\API\filesController as filesControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => '/dashboard',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::apiResource('', filesControllerApi::class)->only('index')->names([
        'index' => 'dashboard.file.index',
    ])->parameters(['dashboard' => 'file']);

    Route::apiResource('file', filesControllerApi::class)->only('show', 'update', 'destroy')->names([
        'show' => 'dashboard.file.show',
        'update' => 'dashboard.file.update',
        'destroy' => 'dashboard.file.destroy',
    ]);
    Route::apiResource('file/create', filesControllerApi::class)->only('store')->names([
        'store' => 'dashboard.file.store',
    ]);
});

//Route::middleware('auth:sanctum')->apiResource('/dashboard', filesControllerApi::class)->names([
//Route::apiResource('/dashboard', filesControllerApi::class)->names([
//    'show' => 'dashboard.file.show',
//    'store' => 'dashboard.file.store',
//    'update' => 'dashboard.file.update',
//    'destroy' => 'dashboard.file.destroy',
//])->parameters(['dashboard' => 'file']);

Route::middleware('auth:sanctum')->get('auth/tokens', [AuthTokensController::class, 'index']);
//Route::middleware('guest:sanctum')->post('auth/tokens', [AuthTokensController::class, 'store']);
Route::middleware('guest:sanctum')->post('auth/tokens', [AuthTokensController::class, 'store']);
Route::middleware('auth:sanctum')->delete('auth/tokens/logout', [AuthTokensController::class, 'current_logout']);
Route::middleware('auth:sanctum')->delete('auth/tokens/{id}', [AuthTokensController::class, 'destroy']);
Route::middleware('auth:sanctum')->delete('auth/tokens/', [AuthTokensController::class, 'logout_all']);



