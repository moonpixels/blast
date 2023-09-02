<?php

use App\Domain\User\Actions\DeletePersonalAccessToken;

it('deletes a personal access token', function () {
    $user = createUser();

    $user->createToken('Test Token');

    $token = $user->tokens()->first();

    DeletePersonalAccessToken::run($token);

    expect($token)->toBeDeleted();
});
