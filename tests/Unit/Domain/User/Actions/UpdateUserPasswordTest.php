<?php

use App\Domain\User\Actions\UpdateUserPassword;
use App\Domain\User\Models\User;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->action = new UpdateUserPassword;

    $this->user = User::factory()->create();

    actingAs($this->user);
});

it('updates a users password', function () {
    $this->action->update($this->user, [
        'current_password' => 'password',
        'password' => 'new-password',
    ]);

    expect(Hash::check('new-password', $this->user->password))->toBeTrue();

    $this->assertTrue(session()->has('success.title'));
    $this->assertTrue(session()->has('success.message'));
});

it('does not update a users password if the current password is invalid', function () {
    $this->action->update($this->user, [
        'current_password' => 'wrong-password',
        'password' => fake()->password(8),
    ]);
})->throws(ValidationException::class);

it('does not update a users password if the new password is too short', function () {
    $this->action->update($this->user, [
        'current_password' => 'old-password',
        'password' => 'short',
    ]);
})->throws(ValidationException::class);

it('does not update a users password if the new password is missing', function () {
    $this->action->update($this->user, [
        'current_password' => 'old-password',
    ]);
})->throws(ValidationException::class);
