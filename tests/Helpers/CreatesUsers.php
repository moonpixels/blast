<?php

use App\Domain\User\Models\User;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Database\Eloquent\Collection;
use Tests\Support\Fty;

/**
 * Create a user with the given attributes.
 */
function createUser(array $attributes = [], array $states = [], bool $withTeams = true): User|Collection
{
    if ($withTeams) {
        $states = array_merge($states, ['withOwnedTeam', 'withMemberTeam']);
    }

    return Fty::build(User::factory(), $states)->create($attributes);
}

/**
 * Create a user and log them in.
 */
function login(array $attributes = [], array $states = []): User
{
    $user = createUser($attributes, $states);

    test()->actingAs($user);

    return $user;
}

/**
 * Log in as the given user.
 */
function loginAs(User $user): User
{
    test()->actingAs($user);

    return $user;
}

/**
 * Act as a guest.
 */
function actingAsGuest(): void
{
    auth()->logout();
}

/**
 * Skip password confirmation middleware.
 */
function skipPasswordConfirmation(): void
{
    test()->mock(RequirePassword::class, function ($mock) {
        $mock->shouldReceive('handle')->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
    });
}
