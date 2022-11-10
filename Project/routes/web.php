<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\fullScheduleController;

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

Route::get('/', [fullScheduleController::class,'viewFullSchedule'])->name('fSchedule');
Route::get('/changeMonth', [fullScheduleController::class,'changeMonth'])->name('fChangeMonth');