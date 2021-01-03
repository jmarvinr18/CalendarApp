<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\EventDaysController;
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

Route::get('/calendar', [CalendarEventController::class, 'index']);
Route::get('/calendar/{id}', [CalendarEventController::class, 'show']);
Route::put('/calendar/{id}', [CalendarEventController::class, 'update']);

Route::post('/calendar', [CalendarEventController::class, 'store']);
Route::post('/event-days', [EventDaysController::class, 'store']);
Route::delete('/event-days/{id}', [EventDaysController::class, 'destroy']);

Route::get('/', function(){
    return view('welcome');
});