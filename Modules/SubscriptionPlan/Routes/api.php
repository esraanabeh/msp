<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SubscriptionPlan\Http\Controllers\API\InvoiceController;
use Modules\SubscriptionPlan\Http\Controllers\API\PaymentMethodController;
use Modules\SubscriptionPlan\Http\Controllers\API\PlanController;
use Modules\SubscriptionPlan\Http\Controllers\API\SubscriptionController;

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

Route::post('/stripe/webhook',[SubscriptionController::class,'webhook']);
Route::post('/stripe/token'  ,[SubscriptionController::class,'createToken']);

Route::group(['middleware' => ['auth:sanctum'],'prefix' => 'subscribe'], function ($router) {
    Route::post('/trial'                  , [SubscriptionController::class , 'subscripeTrial']);
    Route::post('/yearly'                 , [SubscriptionController::class , 'subscripeYearly']);
});
Route::group(['middleware' => ['auth:sanctum']], function ($router) {
    Route::group(['prefix' => 'subscriptionplan'], function ($router) {
        Route::get('/'                        , [PlanController::class, 'index']);
        Route::get('/myplan'                  , [PlanController::class, 'getMyPlan'])->middleware('is_organization_admin');
    });

    Route::group(['prefix' => 'subscribe' ,'middleware' => ['is_organization_admin']], function ($router) {
        Route::post('/upgrade'                , [SubscriptionController::class , 'upgradeFromTrial']);
        Route::post('/cancel'                 , [SubscriptionController::class , 'cancel']);
    });

    Route::group(['prefix' => 'payment','middleware' => ['is_organization_admin']], function ($router) {
        Route::get('/list'                    , [PaymentMethodController::class , 'listPaymentMethods']);
        Route::post('/create'                 , [PaymentMethodController::class , 'createPaymentMethod']);
        Route::post('/update'                 , [PaymentMethodController::class , 'updatePaymentMethod']);
        Route::post('/delete'                 , [PaymentMethodController::class , 'deletePaymentMethod']);
        Route::post('/retrieve'               , [PaymentMethodController::class , 'getCreditCardData']);
        Route::get('/retrieve/default'        , [PaymentMethodController::class , 'getDefaultPaymentMethod']);
    });

    Route::group(['prefix' => 'invoice'], function ($router) {
        Route::get('/list'                   , [InvoiceController::class , 'list']);
        Route::get('/{invoiceId}'            , [InvoiceController::class , 'preview']);
  });
});
