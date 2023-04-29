<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('shows the login page', function () {
    $this->get(route('login'))->assertOk();
});

it('does not show the login page when authenticated', function () {
    $this->actingAs($this->user)->get(route('login'))->assertRedirect(route('dashboard'));
});

it('logs in a user', function () {
    $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($this->user);
});

it('validates the required fields have been provided', function () {
    $this->post(route('login'))->assertInvalid([
        'email',
        'password',
    ]);

    $this->assertGuest();
});

it('does not log in a user with an invalid password', function () {
    $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'wrong-password',
    ])->assertInvalid(['email']);

    $this->assertGuest();
});

it('does not log in a user with an invalid email', function () {
    $this->post(route('login'), [
        'email' => 'not-a-user@example.com',
        'password' => 'password',
    ])->assertInvalid(['email']);

    $this->assertGuest();
});

it('remembers the user', function () {
    $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password',
        'remember' => true,
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($this->user);
    $this->assertNotNull($this->user->remember_token);
});

it('redirects unauthenticated users to to login page', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});
