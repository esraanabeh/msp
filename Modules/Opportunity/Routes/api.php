<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Opportunity\Http\Controllers\API\OpportunityController;

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

Route::group(['prefix' => 'opportunity','middleware' => ['auth:sanctum','is_organization_member']], function ($router) {
    Route::get('/'                    , [OpportunityController::class , 'index']);
    Route::post('/create'             , [OpportunityController::class , 'store']);
    Route::get('/preview/{id}'        , [OpportunityController::class , 'preview']);
    Route::get('/preview/client/{id}' , [OpportunityController::class , 'previewClientOpportunity']);
    Route::delete('/destroy/{id}'     , [OpportunityController::class , 'destroy']);
    Route::post('/update/{id}'        , [OpportunityController::class , 'update']);

});
