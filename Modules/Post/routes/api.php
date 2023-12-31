<?php
use Illuminate\Support\Facades\Route;
use Modules\Post\app\Http\Controllers\PostsController;

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




Route::apiResource('posts', PostsController::class);
Route::get('user/posts', [PostsController::class,'showUserPosts'])->middleware('auth');
