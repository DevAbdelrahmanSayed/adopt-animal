<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\app\Http\Controllers\ChatController;

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

\Illuminate\Support\Facades\Broadcast::routes(['middleware' => ['auth']]);

Route::post('/chat/send', [ChatController::class, 'sendMessage'])->middleware('auth');
Route::get('/chat/messages', [ChatController::class, 'getMessages'])->middleware('auth');
