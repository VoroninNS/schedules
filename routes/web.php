<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

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

Route::get('/upload', function () {
    $parser = new Parser();
    $pdf = $parser->parseFile('/Users/nikita/PhpstormProjects/schedule/storage/app/public/schedule.pdf');
    $text = $pdf->getText();
    $array = explode(']', $text);
    $array[0] = Str::after($array[0], 'Суб бота');
    $array = [];
    return view('upload', compact('array'));
});
