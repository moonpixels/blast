<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

function assertUserNotInDatabase($user): void
{
    assertDatabaseMissing('users', [
        'name' => $user['name'],
        'email' => $user['email'],
    ]);
}

function assertUserInDatabase($user): void
{
    assertDatabaseHas('users', [
        'name' => $user['name'],
        'email' => $user['email'],
    ]);
}
