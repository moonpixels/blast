<?php

use App\Models\User;

beforeEach(function () {
    $this->userData = User::factory()->make()->only(['name', 'email', 'password']);
});

it('should show the registration page', function () {
    $this->get(route('register'))
        ->assertOk();
});

it('should not show the registration page when authenticated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('register'))
        ->assertRedirect(route('dashboard'));
});

it('should not show the registration page when registration is disabled', function () {
    disableRegistration();

    $this->get(route('register'))
        ->assertForbidden();
});

it('should register a new user', function () {
    $response = $this->post(route('register'), $this->userData);

    $response->assertRedirect(route('dashboard'));

    assertUserInDatabase($this->userData);
});

it('should not register a new user when registration is disabled', function () {
    disableRegistration();

    $this->post(route('register'), $this->userData)
        ->assertForbidden();

    assertUserNotInDatabase($this->userData);
});

it('should not register a new user when validation fails', function () {
    $this->post(route('register'))
        ->assertInvalid([
            'name',
            'email',
            'password',
        ]);

    assertUserNotInDatabase($this->userData);
});

it('should not register a new user when the email is already taken', function () {
    User::factory()->create([
        'email' => $this->userData['email'],
    ]);

    $this->post(route('register'), $this->userData)
        ->assertInvalid([
            'email',
        ]);

    assertUserNotInDatabase($this->userData);
});

it('should not register a new user when email is invalid', function () {
    $this->userData['email'] = 'invalid-email';

    $this->post(route('register'), $this->userData)
        ->assertInvalid([
            'email',
        ]);

    assertUserNotInDatabase($this->userData);
});

it('should not register a new user when the password is too short', function () {
    $this->userData['password'] = fake()->password(1, 7);

    $this->post(route('register'), $this->userData)
        ->assertInvalid([
            'password',
        ]);

    assertUserNotInDatabase($this->userData);
});
