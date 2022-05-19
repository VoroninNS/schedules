<?php

use App\Http\Controllers\ScheduleControllers;
use Illuminate\Support\Facades\Route;
use RandomState\Camelot\Camelot;

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
Route::get('byWeek/{group}/{subgroup}', [ScheduleControllers::class, 'byWeek']);
Route::get('/byDay/{group}/{subgroup}/{day}', [ScheduleControllers::class, 'byDay']);
Route::get('/all_groups', [ScheduleControllers::class, 'allGroups'])->name('all_groups');
