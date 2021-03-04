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

    // Админу для всех. Пользователю только для себя
    Route::group(['middleware' => 'security'], function () {
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::get('{id}/contacts', [UserController::class, 'contacts'])->name('user.contacts');
        Route::patch('{id}/contacts/update', [UserController::class, 'updateContacts'])->name('user.contacts.update');
        Route::get('{id}/security', [UserController::class, 'security'])->name('user.security');
        Route::get('{id}/status', [UserController::class, 'status'])->name('user.status');
        Route::patch('{id}/status/update', [UserController::class, 'setStatus'])->name('user.status.update');
        Route::get('{id}/media', [UserController::class, 'media'])->name('user.media');
        Route::patch('{id}/commoninfo/update', [UserController::class, 'commonInfoUpdate'])->name('user.commoninfo.update');
        Route::patch('{id}/security/update', [UserController::class, 'securityUpdate'])->name('user.security.update');
        Route::post('{id}/avatar/update', [UserController::class, 'avatarUpdate'])->name('user.avatar.update');
        Route::delete('{id}/delete', [UserController::class, 'destroy'])->name('user.delete');
    });

    // Только админу
    Route::group(['middleware' => 'admin'], function () {
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/create', [UserController::class, 'store'])->name('user.store');
    });
});

