<?php

use App\Domain\User\Actions\DeleteUser;

beforeEach(function () {
    $this->user = createUser();
});

it('deletes the user', function () {
    expect(DeleteUser::run($this->user))->toBeTrue()
        ->and($this->user)->toBeDeleted();
});
