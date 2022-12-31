<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\ClientController;

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

Route::group(['prefix' => 'clients','middleware' => ['auth:sanctum','is_organization_member']], function ($router) {
    Route::post('/create'                            , [ClientController::class , 'store']);
    Route::get('/{clientId}'                         , [ClientController::class , 'show'])->name('client.show');
    Route::post('/update/{clientId}'                 , [ClientController::class , 'update'])->name('client.update');
    Route::post('/create-MRR-services/{clientId}'    , [ClientController::class , 'storeMRRServices'])->name('store.client.MRR.services');
    Route::post('/create-ORR-services/{clientId}'    , [ClientController::class , 'storeORRServices'])->name('store.client.ORR.services');
    Route::get('/list-MRR-services/{clientId}'       , [ClientController::class , 'ListMRRServices'])->name('list.client.MRR.services');
    Route::get('/list-ORR-services/{clientId}'       , [ClientController::class , 'ListORRServices'])->name('list.client.ORR.services');
    Route::get('/'                                   , [ClientController::class , 'index']);
    Route::post('/create-questions/{clientId}'       , [ClientController::class , 'storeClientQuestions'])->name('store.client.questions');
    Route::post('/update-questions/{clientId}'       , [ClientController::class , 'updateClientQuestions'])->name('update.client.questions');
    Route::delete('/delete-questions/{questionId}'   , [ClientController::class , 'deleteClientQuestions'])->name('delete.client.questions');
    Route::get('/list-questions/{clientId}'          , [ClientController::class , 'listClientQuestions'])->name('list.client.questions');
    Route::post('/change-status/{clientId}'          , [ClientController::class , 'changeClientStatus'])->name('client.change.status');
    Route::delete('/delete/{clientId}'               , [ClientController::class , 'destroy'])->name('client.destroy');
    Route::post('/asign-template/{clientId}'         , [ClientController::class , 'assignTemplateToClient']);
    Route::get('/list-client-template/{clientId}'    , [ClientController::class , 'listClientTemplates']);
    Route::post('/list-tasks/{clientId}'             , [ClientController::class , 'clientTasks']);
    Route::post('/section-due-date/{clientId}'       , [ClientController::class , 'changeSectionDueDate']);
});

Route::group(['prefix' => 'clients','middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
     Route::get('/profits/all-clients'                 , [ClientController::class , 'allClientsProfit']);
    Route::get('/profit/{clientId}'                   , [ClientController::class , 'clientProfit']);
    Route::get('/profits-table/{clientId}'            , [ClientController::class , 'profitsTable']);
    Route::get('/monthly-profits-table/{clientId}'    , [ClientController::class , 'monthlyProfitTable']);
});
