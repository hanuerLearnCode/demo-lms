<?php

use Illuminate\Support\Facades\Route;
use App\Models\Student;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/student', function () {
    return response()->json(Student::all());
});

Route::get('/student/create', function () {

    $student = User::find(6);
    return response()->json($student);
});


Route::get('/users', [\App\Http\Controllers\UserController::class, 'listUsers']);
Route::get('/students', [\App\Http\Controllers\UserController::class, 'listStudents']);
Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'findById']);
