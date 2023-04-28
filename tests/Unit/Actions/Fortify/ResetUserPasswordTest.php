<?php

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->action = new ResetUserPassword;

    $this->user = User::factory()->create();
});

it('resets a users password', function () {
    $this->action->reset($this->user, [
        'password' => 'new-password',
    ]);

    expect(Hash::check('new-password', $this->user->password))->toBeTrue();
});

it('does not reset a users password if it is too short', function () {
    $this->action->reset($this->user, [
        'password' => 'short',
    ]);
})->throws(ValidationException::class);

it('does not reset a users password if it is missing', function () {
    $this->action->reset($this->user, []);
})->throws(ValidationException::class);
