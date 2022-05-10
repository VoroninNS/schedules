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

Route::get('/get/{group}/{subgroup}/{day}', [ScheduleControllers::class, 'get']);
Route::get('/all_groups', [ScheduleControllers::class, 'allGroup']);

Route::get('/upload', function () {
    $filename = 'ИДБ-18-10';
    $schedule = Camelot::lattice(public_path("storage/$filename.pdf"))
                       ->html()
                       ->extract()[0];

    return view('upload', compact('schedule'));
});
