<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/administrator');
    //return view('welcome');
});

Route::get('/api/events/meetings', 'EventController@getMeetings');
Route::get('/api/events/deadlines', 'EventController@getDeadlines');

Route::get('clear-cache', function () {
    //Artisan::call('storage:link');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');

    return "Cache cleared successfully";
});
