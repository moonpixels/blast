<?php

use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = login();
});

test('user settings page requires password confirmation', function () {
    $this->get(route('user.edit'))
        ->assertRedirectToRoute('password.confirm');

    $this->post(route('password.confirm'), [
        'password' => 'password',
    ])->assertRedirectToRoute('user.edit');

    $this->get(route('user.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/profile/index')
        );
});

test('users can update their profile', function () {
    skipPasswordConfirmation();

    $this->put(route('user-profile-information.update'), [
        'name' => 'Updated Test User',
        'email' => 'updated@example.com',
    ])->assertSessionHas('success');

    expect($this->user->name)->toBe('Updated Test User')
        ->and($this->user->email)->toBe('updated@example.com');
});

test('users cannot choose duplicate email addresses', function () {
    skipPasswordConfirmation();

    createUser(attributes: ['email' => 'taken@example.com']);

    $this->put(route('user-profile-information.update'), [
        'email' => 'taken@example.com',
    ])->assertInvalid('email');

    expect($this->user->email)->not->toBe('taken@example.com');
});

test('users can updated their password', function () {
    skipPasswordConfirmation();

    $this->put(route('user-password.update'), [
        'current_password' => 'password',
        'password' => 'new-password',
    ])->assertSessionHas('success');

    expect(Hash::check('new-password', $this->user->password))->toBeTrue();
});

test('password update fails if current password is incorrect', function () {
    skipPasswordConfirmation();

    $this->put(route('user-password.update'), [
        'current_password' => 'wrong-password',
        'password' => 'new-password',
    ])->assertInvalid('current_password');

    expect(Hash::check('password', $this->user->password))->toBeTrue();
});

test('users can delete their account', function () {
    skipPasswordConfirmation();

    $this->delete(route('user.destroy'))
        ->assertRedirectToRoute('home');

    expect($this->user)->toBeDeleted();
});

test('users can update their current team', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    $this->put(route('user.current-team.update'), [
        'current_team_id' => $team->id,
    ])->assertRedirectToRoute('links.index');

    expect($this->user->currentTeam->is($team))->toBeTrue();
});
