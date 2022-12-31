<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\API\TeamController;

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
Route::group(['middleware' => ['auth:sanctum','is_organization_member']], function ($router) {
    Route::get('teams'                       , [TeamController::class, 'index']);
});
Route::group(['middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
    Route::post('teams/create'               , [TeamController::class, 'store']);
    Route::post('teams/update/{id}'          , [TeamController::class, 'update']);
    Route::post('teams/update/status/{id}'   , [TeamController::class, 'updateStatus']);
    Route::delete('teams/delete/{id}'        , [TeamController::class, 'destroy']);
});