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

// protect api
//Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', \App\Http\Controllers\User\UserController::class);

    Route::resource('students', \App\Http\Controllers\User\StudentController::class);

    Route::resource('teachers', \App\Http\Controllers\User\TeacherController::class);

    Route::resource('office_classes', \App\Http\Controllers\OfficeClassController::class);
    Route::resource('faculties', \App\Http\Controllers\FacultyController::class);

    Route::resource('courses', \App\Http\Controllers\CourseController::class);
//});
