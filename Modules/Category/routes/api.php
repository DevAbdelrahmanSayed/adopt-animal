<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\app\Http\Controllers\CategoryController;

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

Route::get('/categories/{category}/posts', [CategoryController::class, 'show'])->middleware('auth');
Route::get('/categories', [CategoryController::class, 'index'])->middleware('auth');
