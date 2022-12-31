<?php

use Illuminate\Http\Request;
use Modules\Organization\Http\Controllers\API\OrganizationController;
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
Route::group(['middleware' => ['auth:sanctum'],'prefix' => 'workspace'], function ($router) {
    Route::post('create'                , [OrganizationController::class, 'create']);
});
Route::group(['middleware' => ['auth:sanctum','is_organization_member'],'prefix' => 'workspace'], function ($router) {
    Route::get('logo'                   , [OrganizationController::class, 'getLogo']);
});
Route::group(['middleware' => ['auth:sanctum','is_organization_admin'],'prefix' => 'workspace'], function ($router) {
    Route::post('profile/update'        , [OrganizationController::class, 'update']);
    Route::get('profile'                , [OrganizationController::class, 'listInfo']);
    Route::post('logo/upload'           , [OrganizationController::class, 'upload']);
    Route::post('logo/update'           , [OrganizationController::class, 'updateLogo']);
    Route::post('terms/update'          , [OrganizationController::class, 'updateOrgTerms']);
    Route::get('terms'                  , [OrganizationController::class, 'getOrgTerms']);
});
