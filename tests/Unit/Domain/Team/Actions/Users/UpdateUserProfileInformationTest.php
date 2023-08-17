<?php

use App\Domain\Team\Actions\Users\UpdateUserProfileInformation;
use App\Domain\Team\Models\User;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->action = new UpdateUserProfileInformation;

    $this->user = User::factory()->create();
});

it('updates a users profile information', function () {
    $this->action->update($this->user, [
        'name' => 'New Name',
        'email' => 'new-email@blst.to',
    ]);

    expect($this->user->name)->toBe('New Name')
        ->and($this->user->email)->toBe('new-email@blst.to');

    $this->assertTrue(session()->has('success.title'));
    $this->assertTrue(session()->has('success.message'));
});

it('does not update a users name to empty', function () {
    $this->action->update($this->user, [
        'name' => '',
        'email' => 'new-email@blst.to',
    ]);
})->throws(ValidationException::class);

it('does not update a users email to empty', function () {
    $this->action->update($this->user, [
        'name' => 'New Name',
        'email' => '',
    ]);
})->throws(ValidationException::class);

it('does not update a users email to an invalid email', function () {
    $this->action->update($this->user, [
        'name' => 'New Name',
        'email' => 'invalid-email',
    ]);
})->throws(ValidationException::class);

it('does not update a users email to an already taken email', function () {
    $existingUser = User::factory()->create();

    $this->action->update($this->user, [
        'name' => 'New Name',
        'email' => $existingUser->email,
    ]);
})->throws(ValidationException::class);
