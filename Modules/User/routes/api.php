<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\LoginController;
use Modules\User\app\Http\Controllers\ProfileController;
use Modules\User\app\Http\Controllers\RegisterController;

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
Route::middleware('auth')->group(function () {
    Route::get('/profiles', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});

Route::prefix('password')->group(function () {
    Route::post('verification', [ProfileController::class, 'resetLinkEmail']);
    Route::post('reset', [ProfileController::class, 'resetPassword']);
});
