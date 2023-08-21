<?php

use App\Domain\User\Actions\UpdateUserProfileInformation;

beforeEach(function () {
    $this->action = new UpdateUserProfileInformation;

    $this->user = createUser();
});

it('updates the users profile information', function () {
    $this->action->update($this->user, [
        'name' => 'Updated Test User',
        'email' => 'updated@example.com',
    ]);

    expect($this->user->name)->toBe('Updated Test User')
        ->and($this->user->email)->toBe('updated@example.com')
        ->and(session('success.title'))->toBe('Profile updated')
        ->and(session('success.message'))->toBe('Your profile has been updated successfully.');
});
