<?php

use Domain\User\Actions\ResetUserPasswordAction;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->action = new ResetUserPasswordAction;

    $this->user = createUser();
});

it('resets the users password', function () {
    $this->action->reset($this->user, [
        'password' => 'new-password',
    ]);

    expect(Hash::check('new-password', $this->user->password))->toBeTrue();
});
