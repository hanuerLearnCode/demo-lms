<?php

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

Route::prefix('/users')->group(function () {
    Route::get('/', [\App\Http\Controllers\User\UserController::class, 'getAll']);
    Route::get('/{id}', [\App\Http\Controllers\User\UserController::class, 'getById']);
    Route::post('/', [\App\Http\Controllers\User\UserController::class, 'create']);
    Route::put('/{id}', [\App\Http\Controllers\User\UserController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\User\UserController::class, 'delete']);
});

Route::prefix('/students')->group(function () {
    Route::get('/', [\App\Http\Controllers\User\StudentController::class, 'getAll']);
    Route::get('/{id}', [\App\Http\Controllers\User\StudentController::class, 'getById']);
    Route::post('/', [\App\Http\Controllers\User\StudentController::class, 'create']);
    Route::put('/{id}', [\App\Http\Controllers\User\StudentController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\User\StudentController::class, 'delete']);
});

