<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
Route::get('chat/provider/{id}', [UserController::class, 'chatWithProvider'])->name('chat')->middleware('seeker');

Route::get('/home-provider', [HomeController::class, 'providerIndex'])->name('provider')->middleware('provider');

Route::get('/profile/{id}', [HomeController::class, 'profile'])->name('profile');

