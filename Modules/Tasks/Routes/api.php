<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\API\TaskController;

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

Route::group(['middleware' => ['auth:sanctum','is_organization_member'],'prefix' => 'tasks'], function ($router) {
    Route::get('/list/{id}'                , [TaskController::class, 'listTasks']);
    Route::post('/update/{taskId}'         , [TaskController::class, 'updateTask']);
    Route::post('update-tasks'     , [TaskController::class, 'update']);
    Route::post('/email'                   , [TaskController::class, 'sendEmail']);
    Route::post('/client/list/{sectionId}' , [TaskController::class, 'listClientTasks']);



});

Route::group(['middleware' => ['auth:sanctum','is_organization_admin'],'prefix' => 'tasks'], function ($router) {
    Route::post('/create/{sectionId}'    , [TaskController::class, 'store']);
    // Route::get('/list/{id}'              , [TaskController::class, 'listTasks']);
    Route::delete('delete/{id}'          , [TaskController::class, 'delete']);
    Route::post('dublicate'              , [TaskController::class, 'dublicate']);
    Route::post('delete'                 , [TaskController::class , 'deleteClientTasks']);
});
