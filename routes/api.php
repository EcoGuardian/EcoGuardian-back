<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SpotController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('api-login');
    Route::post('register', 'register')->name('api-register');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('api-users-index');
        Route::patch('users/me/update', 'update')->name('api-users-update');
        Route::get('users/me', 'currentUser')->name('api-users-currentUser');
        Route::get('users/{id}', 'show')->name('api-users-show');
        Route::delete('users', 'destroy')->name('api-users-destroy');
    });

    Route::controller(SpotController::class)->group(function () {

        Route::middleware(['checkRole:Employee'])->group(function () {
            Route::post('spots/new', 'store')->name('api-spots-store');
            Route::patch('spots/update/{id}', 'update')->name('api-spots-update');
            Route::delete('spots/{id}', 'destroy')->name('api-spots-destroy');
        });

        Route::get('spots', 'index')->name('api-spots-index');
        Route::get('spots/{id}', 'show')->name('api-spots-show');
    });
});
