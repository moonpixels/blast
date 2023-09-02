<?php

use App\Domain\User\Actions\CreatePersonalAccessToken;
use App\Domain\User\Data\PersonalAccessTokenData;
use Laravel\Sanctum\NewAccessToken;

it('creates a personal access token for a user', function () {
    $user = createUser();

    $token = CreatePersonalAccessToken::run($user, PersonalAccessTokenData::from([
        'name' => 'Test Token',
    ]));

    expect($token)->toBeInstanceOf(NewAccessToken::class)
        ->and($user->tokens->count())->toBe(1);
});
