<?php

use Illuminate\Http\Request;
use Modules\Authentication\Http\Controllers\API\AuthenticationController;
use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\API\ForgetPasswordController;
use Modules\Authentication\Http\Controllers\API\ChangePasswordController;
use Modules\Authentication\Http\Controllers\API\PersonalInfoController;

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

//organization register
Route::group(['prefix' => 'auth'],function ($router){
    Route::post('/register'        , [AuthenticationController::class , 'register']);
    Route::post('/verify'          , [AuthenticationController::class , 'verifyCode']);
    Route::post('/login'           , [AuthenticationController::class , 'login'])->name('user.login');
    Route::post('/2fa'             , [AuthenticationController::class , 'completeLogin'])->name('user.login.2fa');
    Route::post('/forget-password' , [ForgetPasswordController::class , 'forgetPassword'])->name('user.forget-password');
    Route::post('/reset-password'  , [ForgetPasswordController::class , 'resetPassword'])->name('user.password.reset');
    Route::post('/resend-pin-code' ,[AuthenticationController::class  , 'resendPinCode'])->name('user.resend.pin-code');
});


Route::group(['middleware' => ['auth:sanctum']], function ($router) {
    Route::get('logout'                   , [AuthenticationController::class , 'logout'])->name('user.logout');
    Route::post('change_password'         , [ChangePasswordController::class , 'changePassword']);
    Route::post('confirm_password'        , [AuthenticationController::class , 'confirmChangePassword']);
    Route::get('2fa-status'               , [AuthenticationController::class , 'twoFAStatus'])->name('user.2fa.status');
    Route::get('2fa-change-request'       , [AuthenticationController::class , 'requestToChange2FAStatus'])->name('user.request.2fa');
    Route::post('2fa-change-status'       , [AuthenticationController::class , 'change2FAStatus'])->name('user.change.2fa.status');
    Route::delete('delete_avatar'         , [PersonalInfoController::class   , 'deleteAvatar']);
    Route::post('update_avatar'           , [PersonalInfoController::class   , 'updateAvatar']);
    Route::get('get_user_info'            , [PersonalInfoController::class   , 'getuserInfo']);

});

Route::group(['middleware' => ['auth:sanctum','is_organization_admin']], function ($router) {
    Route::post('update_personal_profile' , [PersonalInfoController::class   , 'updatepersonalProfile']);

});

