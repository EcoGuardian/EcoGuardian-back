<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SpotController;
use App\Http\Controllers\Api\TypeController;
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

    Route::controller(TypeController::class)->group(function () {

        Route::prefix('spots')->group(function () {

            Route::middleware(['checkRole:Employee'])->group(function () {
                Route::post('types/new', 'store')->name('api-types-store');
                Route::patch('types/{id}', 'update')->name('api-types-update');
                Route::delete('types/{id}', 'destroy')->name('api-types-destroy');

            });

            Route::get('types', 'index')->name('api-types-index');
        });
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

    Route::controller(ReportController::class)->group(function () {

        Route::middleware(['checkRole:Employee'])->group(function () {
        });

        Route::get('reports', 'index')->name('api-reports-index');
        Route::post('reports/new', 'store')->name('api-reports-store');
        Route::get('reports/{id}', 'show')->name('api-reports-show');
        Route::patch('reports/update/{id}', 'update')->name('api-reports-update');
        Route::delete('reports/delete/{id}', 'destroy')->name('api-reports-destroy');

    });

    Route::controller(EventController::class)->group(function () {

        Route::middleware(['checkRole:Employee'])->group(function () {
            Route::post('events/new', 'store')->name('api-events-store');
            Route::patch('events/update/{id}', 'update')->name('api-events-update');
            Route::delete('events/{id}', 'destroy')->name('api-events-destroy');
        });

        Route::get('events', 'index')->name('api-events-index');
        Route::get('events/{id}', 'show')->name('api-events-show');
        Route::post('events/{id}/like', 'ooInterestingToggle')->name('api-ooInterestingToggle-show');

    });
});


