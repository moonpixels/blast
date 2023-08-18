<?php

use App\Domain\User\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

it('confirms the users password and shows the account settings page', function () {
    $this->get(route('user.edit'))
        ->assertRedirectToRoute('password.confirm');

    $this->post(route('password.confirm'), [
        'password' => 'password',
    ])->assertRedirectToRoute('user.edit');

    $this->get(route('user.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('User/Edit')
        );
});

it('does not show the account settings page when the user is not authenticated', function () {
    $this->post(route('logout'));

    $this->get(route('user.edit'))
        ->assertRedirectToRoute('login');
});

it('does not show the account settings page when the users password is not confirmed', function () {
    $this->get(route('user.edit'))
        ->assertRedirectToRoute('password.confirm');

    $this->post(route('password.confirm'), [
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('password');

    $this->get(route('user.edit'))
        ->assertRedirectToRoute('password.confirm');
});
