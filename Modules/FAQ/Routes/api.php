<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\FAQ\Http\Controllers\API\FAQController;

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

Route::group(['middleware' => ['auth:sanctum','is_organization_member'],'prefix' => 'FAQ'], function ($router) {
   Route::get('list_categories'          ,[FAQController::class, 'listCategories']);
   Route::get('list_FAQ/{id}'            ,[FAQController::class, 'listFAQ']);



});

Route::group(['middleware' => ['auth:sanctum','is_organization_admin'],'prefix' => 'FAQ'], function ($router) {
    Route::post('create/category'                  ,[FAQController::class, 'createCategory']);
     Route::post('create/{categoryId}'                  ,[FAQController::class, 'storeFAQ']);
    Route::post('update/{id}'             ,[FAQController::class, 'update']);
    Route::post('update/category/{id}'    ,[FAQController::class, 'updateCategory']);
    Route::delete('category/{id}'         ,[FAQController::class, 'destroyCategory']);
    Route::delete('question/{id}'         ,[FAQController::class, 'destroyQuestion']);

 });
