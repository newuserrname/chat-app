<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login-role', [\App\Http\Controllers\CheckRoleController::class, 'login'])->name('loginRole');
Route::post('/login-role', [\App\Http\Controllers\CheckRoleController::class, 'loginPost'])->name('loginRolePost');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('seeker');
Route::get('chat/provider/{id}', [\App\Http\Controllers\UserController::class, 'chatWithProvider'])->name('chat')->middleware('seeker');

Route::get('/home-provider', [\App\Http\Controllers\HomeController::class, 'providerIndex'])->name('provider')->middleware('provider');

Route::get('/profile/{id}', [\App\Http\Controllers\HomeController::class, 'profile'])->name('profile');

