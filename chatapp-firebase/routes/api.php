<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('chatlist/{id}', [UserController::class, 'getUserById']);
Route::get('/messages/{currentId}/{receiverId}', [UserController::class, 'getMessages']);
Route::post('/send-message', [UserController::class, 'sendMessage']);
Route::get('/get-stampv2', [HomeController::class, 'conversation_stamp']);
