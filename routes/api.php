<?php

use App\Http\Controllers\Api\V1\LinkController;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\TeamInvitationController;
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

Route::middleware(['auth:sanctum'])
    ->prefix('v1')
    ->name('api.')
    ->group(function () {
        // Teams...
        Route::apiResource('teams', TeamController::class);

        // Team invitations...
        Route::apiResource('teams.invitations', TeamInvitationController::class)
            ->except(['update']);

        // Links...
        Route::apiResource('links', LinkController::class);
    });
