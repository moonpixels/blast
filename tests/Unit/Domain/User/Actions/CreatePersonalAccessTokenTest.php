<?php

use Domain\User\Actions\CreatePersonalAccessTokenAction;
use Domain\User\DTOs\PersonalAccessTokenData;
use Laravel\Sanctum\NewAccessToken;

it('creates a personal access token for a user', function () {
    $user = createUser();

    $token = CreatePersonalAccessTokenAction::run($user, PersonalAccessTokenData::from([
        'name' => 'Test Token',
    ]));

    expect($token)->toBeInstanceOf(NewAccessToken::class)
        ->and($user->tokens->count())->toBe(1);
});
