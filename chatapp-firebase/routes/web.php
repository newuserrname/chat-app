<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeekerChatController;
use App\Http\Controllers\ProviderChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckRoleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/login-role', [CheckRoleController::class, 'login'])->name('loginRole');
Route::post('/login-role', [CheckRoleController::class, 'loginPost'])->name('loginRolePost');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('seeker');


Route::get('/home-provider', [HomeController::class, 'providerIndex'])->name('provider')->middleware('provider');

Route::get('/profile/{id}', [HomeController::class, 'profile'])->name('profile');



Route::prefix('/seeker-conversation')->middleware('seeker')->group(function () {
    Route::get('/chat', [SeekerChatController::class, 'chatWithProvider'])->name('seeker_conversation.chat');
    Route::get('/chat/{id}', [SeekerChatController::class, 'chatWithProvider'])->name('seeker_conversation.chat');
    Route::get('/chat-mobile/{id}', [SeekerChatController::class, 'chatMobileWithProvider'])->name('seeker_conversation.chat');
    Route::get('/chatlist', [SeekerChatController::class, 'getChatList']);
    Route::get('/messages/{receiverId}', [SeekerChatController::class, 'getMessages']);
    Route::post('/send-message', [SeekerChatController::class, 'sendMessage']);
    Route::post('/send-file-attach', [SeekerChatController::class, 'sendFileAttach']);
    Route::get('/get-stampv2', [SeekerChatController::class, 'conversation_stamp']);
    Route::post('/send-stampv2', [SeekerChatController::class, 'sendStampv2']);
});


Route::prefix('/provider-conversation')->middleware('provider')->group(function () {
    Route::get('/chat', [ProviderChatController::class, 'chatWithSeeker'])->name('provider_conversation.chat');
    Route::get('/chat/{id}', [ProviderChatController::class, 'chatWithSeeker'])->name('provider_conversation.chat');
    Route::get('/chat-mobile/{id}', [ProviderChatController::class, 'chatMobileWithSeeker'])->name('provider_conversation.chat');
    Route::get('/chatlist', [ProviderChatController::class, 'getChatList']);
    Route::get('/messages/{receiverId}', [ProviderChatController::class, 'getMessages']);
    Route::post('/send-message', [ProviderChatController::class, 'sendMessage']);
    Route::post('/send-file-attach', [ProviderChatController::class, 'sendFileAttach']);
    Route::get('/get-stampv2', [ProviderChatController::class, 'conversation_stamp']);
    Route::post('/send-stampv2', [ProviderChatController::class, 'sendStampv2']);
});

