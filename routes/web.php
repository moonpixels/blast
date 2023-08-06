<?php

use App\Http\Controllers\Web\Links\LinkController;
use App\Http\Controllers\Web\Redirects\AuthenticatedRedirectController;
use App\Http\Controllers\Web\Redirects\ExpiredRedirectController;
use App\Http\Controllers\Web\Redirects\ReachedVisitLimitRedirectController;
use App\Http\Controllers\Web\Redirects\RedirectController;
use App\Http\Controllers\Web\Teams\AcceptedInvitationController;
use App\Http\Controllers\Web\Teams\InvitationController;
use App\Http\Controllers\Web\Teams\ResentInvitationController;
use App\Http\Controllers\Web\Teams\TeamController;
use App\Http\Controllers\Web\Teams\TeamInvitationController;
use App\Http\Controllers\Web\Teams\TeamMembershipController;
use App\Http\Controllers\Web\Users\UserController;
use App\Http\Controllers\Web\Users\UserCurrentTeamController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    // Links...
    Route::controller(LinkController::class)
        ->name('links.')
        ->group(function () {
            Route::get('/links', 'index')->name('index');
            Route::post('/links', 'store')->name('store');
            Route::delete('/links/{link}', 'destroy')->name('destroy');
        });

    // Current user...
    Route::controller(UserController::class)
        ->middleware(['password.confirm'])
        ->name('user.')
        ->group(function () {
            Route::delete('/user', 'destroy')->name('destroy');
            Route::get('/user/edit', 'edit')->name('edit');
        });

    // Current team...
    Route::controller(UserCurrentTeamController::class)
        ->name('user.current-team.')
        ->group(function () {
            Route::put('/user/current-team', 'update')->name('update');
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
        ->name('teams.invitations.')
        ->group(function () {
            Route::post('/teams/{team}/invitations', 'store')->name('store');
        });

    // Invitations...
    Route::controller(InvitationController::class)
        ->name('invitations.')
        ->group(function () {
            Route::delete('invitations/{invitation}', 'destroy')->name('destroy');
        });

    // Accepted invitations...
    Route::controller(AcceptedInvitationController::class)
        ->name('accepted-invitations.')
        ->group(function () {
            Route::get('accepted-invitations/{invitation}', 'show')
                ->middleware(['signed'])
                ->name('show');
        });

    // Resent invitations...
    Route::controller(ResentInvitationController::class)
        ->name('resent-invitations.')
        ->group(function () {
            Route::post('resent-invitations', 'store')->name('store');
        });

    // Team memberships...
    Route::controller(TeamMembershipController::class)
        ->name('team-memberships.')
        ->group(function () {
            Route::delete('/team-memberships/{teamMembership}', 'destroy')->name('destroy');
        });
});

// Authenticated redirects...
Route::get('/{alias}/password', [AuthenticatedRedirectController::class, 'create'])->name('authenticated-redirect');
Route::post('/{alias}/password', [AuthenticatedRedirectController::class, 'store']);

// Expired redirects...
Route::get('redirects/expired', [ExpiredRedirectController::class, 'show'])->name('expired-redirect');

// Reached visit limit redirects...
Route::get('redirects/reached-visit-limit', [ReachedVisitLimitRedirectController::class, 'show'])
    ->name('reached-visit-limit-redirect');

// Redirects...
Route::get('/{alias}', [RedirectController::class, 'create'])->name('redirect');
