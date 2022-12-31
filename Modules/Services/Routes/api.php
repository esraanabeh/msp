<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\Api\ServicesPricingController;
use Modules\Services\Http\Controllers\Api\MasterServiceAgreementController;

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
    Route::group(['prefix' => "services"], function ($router) {
        Route::get(''                        , [ServicesPricingController::class, 'index']);
        Route::get('/MRR'                   , [ServicesPricingController::class , 'listMRRQuestions'])->name('service.MRR.index');
        Route::get('/ORR'                    , [ServicesPricingController::class , 'listORRQuestions'])->name('service.ORR.index');
    });
});

Route::group(['middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
    Route::group(['prefix' => "services"], function ($router) {
        Route::post('create'                 , [ServicesPricingController::class, 'store']);
        Route::post('update_service'         , [ServicesPricingController::class, 'update']);
        Route::delete('delete/{id}'          , [ServicesPricingController::class, 'delete']);
    });

    Route::group(['prefix' => "msa"], function ($router) {
        Route::post('create'                , [MasterServiceAgreementController::class, 'store']);
        Route::get(''                       , [MasterServiceAgreementController::class, 'index']);
        Route::delete('delete/{id}'         , [MasterServiceAgreementController::class, 'delete']);
        Route::post('update-default/{id}'   , [MasterServiceAgreementController::class, 'updateDefault']);
    });


});
