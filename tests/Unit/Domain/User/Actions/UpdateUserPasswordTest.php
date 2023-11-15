<?php

use Domain\User\Actions\UpdateUserPasswordAction;

beforeEach(function () {
    $this->action = new UpdateUserPasswordAction;

    $this->user = login();
});

it('updates the users password', function () {
    $this->action->update($this->user, [
        'current_password' => 'password',
        'password' => 'new-password',
    ]);

    expect(Hash::check('new-password', $this->user->password))->toBeTrue()
        ->and(session('success.title'))->toBe('Password updated')
        ->and(session('success.message'))->toBe('Your password has been updated successfully.');
});
