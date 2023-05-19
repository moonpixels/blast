<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->tfaUser = User::factory()->withTwoFactorAuthentication()->create();
});

it('shows the two-factor challenge page on login when enabled', function () {
    $this->post(route('login'), [
        'email' => $this->tfaUser->email,
        'password' => 'password',
    ])->assertRedirectToRoute('two-factor.login');
});

it('does not show the two-factor challenge page on login when disabled', function () {
    $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ])->assertRedirect(config('fortify.home'));
});
