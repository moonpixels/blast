<?php

use App\Domain\Team\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('shows the login page', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->where('status', null)
        );
});

it('does not show the login page when authenticated', function () {
    $this->actingAs($this->user)->get(route('login'))->assertRedirect(config('fortify.home'));
});

it('logs in a user', function () {
    $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ])->assertRedirect(config('fortify.home'));

    $this->assertAuthenticatedAs($this->user);
});

it('does not log the user in when the required fields are missing', function () {
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
        'email' => 'not-a-user@blst.to',
        'password' => 'password',
    ])->assertInvalid(['email']);

    $this->assertGuest();
});

it('remembers the user', function () {
    $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password',
        'remember' => true,
    ])->assertRedirect(config('fortify.home'));

    $this->assertAuthenticatedAs($this->user);
    $this->assertNotNull($this->user->remember_token);
});

it('redirects unauthenticated users to to login page', function () {
    $this->get(config('fortify.home'))->assertRedirectToRoute('login');
});
