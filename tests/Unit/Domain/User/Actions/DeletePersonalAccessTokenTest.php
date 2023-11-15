<?php

use Domain\User\Actions\DeletePersonalAccessTokenAction;

it('deletes a personal access token', function () {
    $user = createUser();

    $user->createToken('Test Token');

    $token = $user->tokens()->first();

    DeletePersonalAccessTokenAction::run($token);

    expect($token)->toBeDeleted();
});
