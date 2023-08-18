<?php

use App\Http\Controllers\Web\Links\LinkController;
use App\Http\Controllers\Web\Redirects\RedirectController;
use App\Http\Controllers\Web\Teams\TeamController;
use App\Http\Controllers\Web\Teams\TeamInvitationController;
use App\Http\Controllers\Web\Teams\TeamMemberController;
use App\Http\Controllers\Web\User\CurrentTeamController;
use App\Http\Controllers\Web\User\CurrentUserController;
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
    return Inertia::render('Home', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Links...
    Route::controller(LinkController::class)
        ->name('links.')
        ->group(function () {
            Route::post('/links', 'store')->name('store');
            Route::get('/links', 'index')->name('index');
            Route::put('/links/{link}', 'update')->name('update');
            Route::delete('/links/{link}', 'destroy')->name('destroy');
        });

    // Current user...
    Route::controller(CurrentUserController::class)
        ->name('user.')
        ->group(function () {
            // Protected routes...
            Route::middleware(['password.confirm'])->group(function () {
                Route::delete('/user', 'destroy')->name('destroy');
                Route::get('/user/edit', 'edit')->name('edit');
            });

            // Current team...
            Route::controller(CurrentTeamController::class)
                ->name('current-team.')
                ->group(function () {
                    Route::put('/user/current-team', 'update')->name('update');
                });
        });

    // Teams...
    Route::controller(TeamController::class)
        ->name('teams.')
        ->group(function () {
            Route::post('/teams', 'store')->name('store');
            Route::get('/teams/{team}', 'show')->name('show');
            Route::put('/teams/{team}', 'update')->name('update');
            Route::delete('/teams/{team}', 'destroy')->name('destroy');
        });

    // Team invitations...
    Route::controller(TeamInvitationController::class)
        ->scopeBindings()
        ->name('teams.invitations.')
        ->group(function () {
            Route::post('/teams/{team}/invitations', 'store')->name('store');
            Route::delete('/teams/{team}/invitations/{invitation}', 'destroy')->name('destroy');
            Route::get('/teams/{team}/invitations/{invitation}/accept', 'accept')->name('accept');
            Route::get('/teams/{team}/invitations/{invitation}/resend', 'resend')->name('resend');
        });

    // Team members...
    Route::controller(TeamMemberController::class)
        ->scopeBindings()
        ->name('teams.members.')
        ->group(function () {
            Route::delete('/teams/{team}/members/{member}', 'destroy')->name('destroy');
        });
});

// Redirects...
Route::controller(RedirectController::class)
    ->name('redirects.')
    ->group(function () {
        Route::get('/{link:alias}', 'show')->name('show');
        Route::post('/{link:alias}', 'authenticate')->name('authenticate');
    });
