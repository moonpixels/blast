<?php

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->action = new UpdateUserProfileInformation;

    $this->user = User::factory()->create();
});

it('updates a users profile information', function () {
    $this->action->update($this->user, [
        'name' => 'New Name',
        'email' => 'new-email@example.com',
    ]);

    expect($this->user->name)->toBe('New Name')
        ->and($this->user->email)->toBe('new-email@example.com')
        ->and(session('success.title'))->toBe(__('account.profile_info_update_success.title'))
        ->and(session('success.message'))->toBe(__('account.profile_info_update_success.message'));
});

it('does not update a users name to empty', function () {
    $this->action->update($this->user, [
        'name' => '',
        'email' => 'new-email@example.com',
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
