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

Route::get('/docs', function () {
    return view('swagger.index');
});

// Clear application cache:
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return view('welcome');
});

//Clear route cache:
Route::get('/route-cache', function() {
	Artisan::call('route:cache');
    return view('welcome');
});

//Clear config cache:
Route::get('/config-cache', function() {
 	Artisan::call('config:cache');
 	return view('welcome');
});
Route::prefix('admin')->group(function () {  
    Auth::routes();
});

Route::prefix('admin')->middleware('auth')->group(function () { 
    


});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
