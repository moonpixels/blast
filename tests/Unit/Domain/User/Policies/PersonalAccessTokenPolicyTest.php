<?php

use App\Domain\User\Policies\PersonalAccessTokenPolicy;

beforeEach(function () {
    $this->policy = new PersonalAccessTokenPolicy();

    $this->user = createUser();

    $this->user->createToken('Test Token');

    $this->token = $this->user->tokens()->first();
});

it('only lets the user view their own tokens', function () {
    expect($this->policy->view($this->user, $this->token))->toBeTrue()
        ->and($this->policy->view(createUser(), $this->token))->toBeFalse();
});

it('only lets the user delete their own tokens', function () {
    expect($this->policy->delete($this->user, $this->token))->toBeTrue()
        ->and($this->policy->delete(createUser(), $this->token))->toBeFalse();
});
