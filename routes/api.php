<?php

use Illuminate\Http\Request;


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgetPasswordController;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
    Route::get('/activity/log'           , function(){
        return Activity::all();
    });

});


