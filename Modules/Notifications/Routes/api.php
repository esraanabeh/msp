<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Notifications\Http\Controllers\API\NotificationController;


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

Route::group(['middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
    Route::post('notification/update_status'       ,[NotificationController::class, 'update']);
});
Route::group(['middleware' => ['auth:sanctum','is_organization_member']], function ($router) {
    Route::get('list_notifications'                 ,[NotificationController::class, 'index']);
    Route::get('list_admin/user_notifications'      ,[NotificationController::class, 'listNotifications']);
    Route::get('preview_notification_details/{id}'  ,[NotificationController::class, 'NotificationsDetails']);
});
