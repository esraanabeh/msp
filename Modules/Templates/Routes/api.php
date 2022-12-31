<?php

use Illuminate\Http\Request;
use Modules\Templates\Http\Controllers\API\TemplatesController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:sanctum','is_organization_member'],'prefix' => 'template'], function ($router) {

    Route::get('/list/all'                 , [TemplatesController::class, 'index']);
    Route::get('/list/my/template'         , [TemplatesController::class, 'myTemplate']);
    Route::get('/section/list/{id}'        , [TemplatesController::class, 'listSections']);
    Route::get('/{id}'                     , [TemplatesController::class, 'showTemplate']);
});

Route::group(['middleware' => ['auth:sanctum','is_organization_member'],'prefix' => 'template'], function ($router) {
    Route::post('/create'                  , [TemplatesController::class, 'createTemplate']);
    Route::post('/update/{id}'             , [TemplatesController::class, 'update']);
    Route::post('/assign/{Clientid}'       , [TemplatesController::class, 'clientTemplate']);
});

Route::group(['middleware' => ['auth:sanctum','is_organization_member'],'prefix' => 'template/section'], function ($router) {
    Route::get('show/{id}'                 , [TemplatesController::class, 'showSection']);
    Route::post('/create'                  , [TemplatesController::class, 'createNewSection']);
    Route::delete('/delete/{id}'           , [TemplatesController::class, 'destroySection']);
    Route::post('/update/{id}'             , [TemplatesController::class, 'updateSection']);
}); 
Route::group(['middleware' => ['auth:sanctum','is_organization_admin'],'prefix' => 'template'], function ($router) {
    Route::delete('/delete/{id}'           , [TemplatesController::class, 'destroyTemplate']);
});
