<?php

use App\Http\Controllers\Api\V1\LinkController;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\TeamInvitationController;
use App\Http\Controllers\Api\V1\TeamMemberController;
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

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('v1')
    ->name('api.')
    ->group(function () {
        // Teams...
        Route::apiResource('teams', TeamController::class);

        // Team invitations...
        Route::apiResource('teams.invitations', TeamInvitationController::class)
            ->except(['update']);

        // Team members...
        Route::apiResource('teams.members', TeamMemberController::class)
            ->only(['index', 'show', 'destroy']);

        // Links...
        Route::apiResource('links', LinkController::class);
    });
