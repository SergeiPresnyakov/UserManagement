<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

// Общедоступно
Route::get('/', [UserController::class, 'index'])->name('index');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'create'])->name('user.register');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'auth'])->name('user.login');
Route::get('user/{id}/profile', [UserController::class, 'show'])->name('user.profile');

// Только авторизованному пользователю
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::get('{id}/security', [UserController::class, 'security'])->name('user.security');
    Route::get('{id}/status', [UserController::class, 'status'])->name('user.status');
    Route::get('{id}/media', [UserController::class, 'media'])->name('user.media');
    // Только админу
    Route::get('/create', [UserController::class, 'create'])->middleware('admin')->name('user.create');
});

