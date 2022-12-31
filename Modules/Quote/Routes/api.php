<?php

use Illuminate\Http\Request;
use Modules\Quote\Http\Controllers\QuoteController;
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

Route::middleware('auth:api')->get('/quote', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'quotes/template','middleware'=>['auth:sanctum','is_organization_member']], function ($router) {
    Route::post('/update/{templateId}'  , [QuoteController::class , 'update'])->name('quote.template.store');
    Route::get('/'                      , [QuoteController::class , 'show'])->name('quote.template.show');
});


Route::group(['prefix'=>'quotes/section','middleware' => ['auth:sanctum','is_organization_member']], function ($router) {
    Route::post('/create/{templateId}'  , [QuoteController::class , 'store'])->name('quote.section.store');
    Route::delete('/delete/{id}'        , [QuoteController::class , 'destroy'])->name('quote.section.destroy');
});

Route::group(['prefix'=>'client/quote','middleware' => ['auth:sanctum','is_organization_member']],function ($router) {
    Route::get('/generate/{clientId}'     ,[QuoteController::class ,'generateClientQuote'])->name('generate.client.quote');
    Route::post('/send/{clientId}'        ,[QuoteController::class ,'sendClientQuote'])->name('send.client.quote');
    Route::post('/save/{clientId}'        ,[QuoteController::class ,'saveClientQuote'])->name('save.client.quote');
    Route::get('/get/{clientId}'          ,[QuoteController::class , 'showClientQuote'])->name('show.client.quote');
});

Route::post('/quotes/check-code'           ,[QuoteController::class , 'checkQuoteLink'])->name('code-check');

Route::post('/quotes/client-decision'      ,[QuoteController::class , 'ClientLink'])->name('quote-decision');

