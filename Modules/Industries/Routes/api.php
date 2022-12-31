<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Industries\Http\Controllers\API\IndustriesController;

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

Route::group(['middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
    Route::post('create_industry'          ,[IndustriesController::class, 'store']);
    Route::get('list_industries'           ,[IndustriesController::class, 'index']);
    Route::post('update_industries'        ,[IndustriesController::class, 'update']);
    Route::delete('delete_industries/{id}' ,[IndustriesController::class, 'destroy']);

});
