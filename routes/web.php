<?php

use App\Http\Controllers\ScheduleControllers;
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

Route::get('/', [ScheduleControllers::class, 'index']);
Route::get('/all_groups', [ScheduleControllers::class, 'allGroups'])->name('all_groups');
Route::get('/all_teachers', [ScheduleControllers::class, 'allTeachers'])->name('all_teachers');

Route::prefix('student')->group(function () {
    Route::get('/byWeek/{group}/{subgroup}', [ScheduleControllers::class, 'studentByWeek'])->name('student_byWeek');
    Route::get('/byDay/{group}/{subgroup}/{day}', [ScheduleControllers::class, 'studentByDay'])->name('student_byDay');
});

Route::prefix('teacher')->group(function () {
    Route::get('/byWeek/{teacher}', [ScheduleControllers::class, 'teacherByWeek'])->name('teacher_byWeek');
    Route::get('/byDay/{teacher}/{day}', [ScheduleControllers::class, 'teacherByDay'])->name('teacher_byDay');
});
