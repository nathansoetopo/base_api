<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PasswordController;

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
// PAKE YANG INI AJA
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// PAKE INI
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/email/verification-notification', [EmailController::class, 'emailSend'])->name('verification.send');
    Route::get('verify-email/{id}/{hash}', [EmailController::class, 'verify'])->name('verification.verify');
    Route::get('/logout', [AuthController::class, 'logout']);
});
Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/data', [AuthController::class, 'dataUser'])->middleware('verified');
});
Route::group(['middleware' => 'guest'], function(){
    Route::post('forgot-password', [PasswordController::class, 'forgotPassword']);
    Route::post('reset-password',[PasswordController::class, 'resetPassword']);
});
