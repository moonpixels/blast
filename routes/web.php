<?php

use App\Http\Controllers\Web\AccountSettingsController;
use App\Http\Controllers\Web\CurrentTeamController;
use App\Http\Controllers\Web\CurrentUserController;
use App\Http\Controllers\Web\LinkController;
use App\Http\Controllers\Web\TeamController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Links...
    Route::controller(LinkController::class)
        ->name('links.')
        ->group(function () {
            Route::get('/links', 'index')->name('index');
        });

    // Account settings...
    Route::controller(AccountSettingsController::class)
        ->name('account-settings.')
        ->group(function () {
            Route::get('/account/settings', 'show')->name('show');
        });

    // Current user...
    Route::controller(CurrentUserController::class)
        ->name('current-user.')
        ->group(function () {
            Route::delete('/user', 'destroy')->name('destroy');
        });

    // Teams...
    Route::controller(TeamController::class)
        ->name('teams.')
        ->group(function () {
            Route::get('/teams/{team}', 'show')->name('show');
            Route::post('/teams', 'store')->name('store');
            Route::put('/teams/{team}', 'update')->name('update');
            Route::delete('/teams/{team}', 'destroy')->name('destroy');
        });

    // Current team...
    Route::controller(CurrentTeamController::class)
        ->name('current-team.')
        ->group(function () {
            Route::put('/current-team', 'update')->name('update');
        });
});
