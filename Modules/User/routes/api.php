<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\LoginController;
use Modules\User\app\Http\Controllers\OtpController;
use Modules\User\app\Http\Controllers\ProfileController;
use Modules\User\app\Http\Controllers\RegisterController;
use Modules\User\app\Http\Controllers\ResetPasswordController;

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

Route::middleware('guest')->group(function () {
    Route::post('register', [RegisterController::class,'storeRegister']);
    Route::post('login', [LoginController::class,'storeLogin']);
    Route::get('login', [LoginController::class,'errors'])->name('login');
});
Route::middleware('auth:user')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile/update', [ProfileController::class, 'update']);
    Route::put('/profile/update/password', [ProfileController::class, 'changePassword']);
});
Route::prefix('otp')->group(function () {
    Route::post('verify', [OtpController::class, 'verify'])->middleware('auth');
    Route::post('resend', [OtpController::class, 'resendOtp'])->middleware('auth');
});

Route::prefix('password')->group(function () {
    Route::post('verification', [ResetPasswordController::class, 'resetLinkEmail'])->middleware('guest');
    Route::post('reset', [ResetPasswordController::class, 'resetPassword'])->middleware('auth:user','verify.otp');
});
