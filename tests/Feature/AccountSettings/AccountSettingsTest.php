<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->withTwoFactorAuthentication()->create();

    $this->actingAs($this->user);
});

it('confirms the users password and shows the account settings page', function () {
    $this->get(route('account-settings'))
        ->assertRedirect(route('password.confirm'));

    $this->post(route('password.confirm'), [
        'password' => 'password',
    ])->assertRedirect(route('account-settings'));

    $this->get(route('account-settings'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('AccountSettings/Edit')
        );
});

it('does not show the account settings page when the user is not authenticated', function () {
    $this->post(route('logout'));

    $this->get(route('account-settings'))
        ->assertRedirect(route('login'));
});

it('does not show the account settings page when the users password is not confirmed', function () {
    $this->get(route('account-settings'))
        ->assertRedirect(route('password.confirm'));

    $this->post(route('password.confirm'), [
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('password');

    $this->get(route('account-settings'))
        ->assertRedirect(route('password.confirm'));
});
