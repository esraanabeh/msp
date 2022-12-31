<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Question\Http\Controllers\QuestionController;

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


Route::group(['prefix' => 'questions','middleware' => ['auth:sanctum','is_organization_member']], function ($router) {
    Route::get('/'                 , [QuestionController::class , 'index'])->name('question.index');
    Route::post('/create'          , [QuestionController::class , 'store'])->name('question.store');
    Route::delete('/delete/{id}'   , [QuestionController::class , 'destroy'])->name('question.destroy');

});
