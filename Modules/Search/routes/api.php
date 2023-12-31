<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Search\app\Http\Controllers\SearchController;

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

Route::get('search', [SearchController::class, 'index'])->middleware('auth');
