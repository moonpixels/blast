<?php

use App\Models\User;

beforeEach(function () {
    $this->userData = User::factory()->make()->only(['name', 'email', 'password']);
});

it('shows the registration page', function () {
    $this->get(route('register'))->assertOk();
});

it('does not show the registration page when authenticated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('register'))->assertRedirect(route('dashboard'));
});

it('does not show the registration page when registration is disabled', function () {
    disableRegistration();

    $this->get(route('register'))->assertForbidden();
});

it('registers a new user', function () {
    $this->post(route('register'), $this->userData)->assertRedirect(route('dashboard'));

    assertUserInDatabase($this->userData);
    $this->assertAuthenticatedAs(User::whereEmail($this->userData['email'])->first());
});

it('does not register a new user when registration is disabled', function () {
    disableRegistration();

    $this->post(route('register'), $this->userData)->assertForbidden();

    assertUserNotInDatabase($this->userData);
});

it('validates the required fields have been provided', function () {
    $this->post(route('register'))->assertInvalid([
        'name',
        'email',
        'password',
    ]);

    assertUserNotInDatabase($this->userData);
});

it('validates the email is available', function () {
    User::factory()->create([
        'email' => $this->userData['email'],
    ]);

    $this->post(route('register'), $this->userData)->assertInvalid([
        'email',
    ]);

    assertUserNotInDatabase($this->userData);
});

it('validates the email is valid', function () {
    $this->userData['email'] = 'invalid-email';

    $this->post(route('register'), $this->userData)->assertInvalid([
        'email',
    ]);

    assertUserNotInDatabase($this->userData);
});

it('validates the password is not too short', function () {
    $this->userData['password'] = fake()->password(1, 7);

    $this->post(route('register'), $this->userData)->assertInvalid([
        'password',
    ]);

    assertUserNotInDatabase($this->userData);
});
