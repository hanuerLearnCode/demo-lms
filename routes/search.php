<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\StudentController;
use App\Http\Controllers\User\TeacherController;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'throttle:60,1',
])->group(function () {

    // add server-side rate limiting -> optimize search
    Route::get('/faculties/search', [\App\Http\Controllers\FacultyController::class, 'search'])->name('faculties.search');

    // add server-side rate limiting -> optimize search
    Route::get('/office_classes/search', [\App\Http\Controllers\OfficeClassController::class, 'search'])->name('office_classes.search');

    // add server-side rate limiting -> optimize search
    Route::get('/courses/search', [\App\Http\Controllers\CourseController::class, 'search'])->name('courses.search');

    // add server-side rate limiting -> optimize search
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');

    // add server-side rate limiting -> optimize search
    Route::get('/teachers/search', [TeacherController::class, 'search'])->name('teachers.search');

});
