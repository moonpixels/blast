<?php

use App\Domain\User\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->withTwoFactorAuthentication()->create();

    $this->actingAs($this->user);
});

it('shows the confirm password page', function () {
    $this->get(route('password.confirm'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/ConfirmPassword')
        );
});

it('does not show the confirm password page to guests', function () {
    $this->post(route('logout'));

    $this->get(route('password.confirm'))->assertRedirectToRoute('login');
});

it('confirms the password', function () {
    $this->post(route('password.confirm'), [
        'password' => 'password',
    ])->assertRedirect(config('fortify.home'));

    $this->assertTrue(session()->has('auth.password_confirmed_at'));
});

it('does not confirm the password when the password is missing', function () {
    $this->post(route('password.confirm'))->assertInvalid(['password']);

    $this->assertFalse(session()->has('auth.password_confirmed_at'));
});

it('does not confirm the password when the password is incorrect', function () {
    $this->post(route('password.confirm'), [
        'password' => 'wrong-password',
    ])->assertInvalid(['password']);

    $this->assertFalse(session()->has('auth.password_confirmed_at'));
});

it('does not confirm the password when the user is not authenticated', function () {
    $this->post(route('logout'));

    $this->post(route('password.confirm'), [
        'password' => 'password',
    ])->assertRedirectToRoute('login');

    $this->assertFalse(session()->has('auth.password_confirmed_at'));
});
