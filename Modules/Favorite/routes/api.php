<?php

use Illuminate\Support\Facades\Route;
use Modules\Favorite\app\Http\Controllers\FavoriteController;

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

Route::controller(FavoriteController::class)->middleware('auth')->group(function () {

    Route::get('/favorites', 'index');

    Route::post('/posts/{post}/favorite', 'addFavorite');

    Route::delete('/posts/{post}/favorite', 'removeFavorite');

});
