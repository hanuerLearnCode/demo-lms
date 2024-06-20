<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/courses', function () {
        return view('courses.index');
    })->name('course.index');

//    Route::get('/students', function () {
//        return view('students.index');
//    })->name('students.index');
//
//    Route::get('/teachers', function () {
//        return view('teachers.index');
//    })->name('teachers.index');
//
//    Route::get('/classes', function () {
//        return view('classes.index');
//    })->name('classes.index');
});
