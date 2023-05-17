<?php

use Illuminate\Auth\Middleware\RequirePassword;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

/**
 * Assert that the given user is not in the database.
 */
function assertUserNotInDatabase($user): void
{
    assertDatabaseMissing('users', [
        'name' => $user['name'],
        'email' => $user['email'],
    ]);
}

/**
 * Assert that the given user is in the database.
 */
function assertUserInDatabase($user): void
{
    assertDatabaseHas('users', [
        'name' => $user['name'],
        'email' => $user['email'],
    ]);
}

/**
 * Disable the need to confirm the password.
 */
function withoutPasswordConfirmation(): void
{
    test()->mock(RequirePassword::class, function ($mock) {
        $mock->shouldReceive('handle')->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
    });
}
