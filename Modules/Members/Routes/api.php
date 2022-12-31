<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Members\Http\Controllers\API\MemberController;

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
    Route::get('get_members'           , [MemberController::class, 'index']);
});
Route::group(['middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
    Route::post('create_members'       , [MemberController::class, 'store'])->name('staff.member.create');
    Route::post('member/update/{id}'   , [MemberController::class, 'update']);
    Route::delete('member/delete/{id}' , [MemberController::class, 'destroy']);
    Route::post('member/update_status' , [MemberController::class, 'update_status']);
});
