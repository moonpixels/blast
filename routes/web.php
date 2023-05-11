<?php

use App\Http\Controllers\Web\AccountSettingsController;
use App\Http\Controllers\Web\LinksController;
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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::controller(LinksController::class)->group(function () {
        Route::get('/links', 'index')->name('links');
    });

    Route::controller(AccountSettingsController::class)
        ->middleware(['password.confirm'])
        ->group(function () {
            Route::get('/settings/account', 'edit')->name('account-settings');
        });
});
