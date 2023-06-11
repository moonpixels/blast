<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();

    Notification::fake();

    $this->post(route('password.request'), [
        'email' => $this->user->email,
    ]);

    Notification::assertSentTo($this->user, ResetPassword::class, function ($notification) {
        $this->token = $notification->token;

        return true;
    });
});

it('shows the reset password page', function () {
    $this->get(route('password.reset', [
        'token' => $this->token,
        'email' => $this->user->email,
    ]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Auth/ResetPassword')
        ->where('email', $this->user->email)
        ->where('token', $this->token)
    );
});

it('does not show the reset password page when authenticated', function () {
    $this->actingAs($this->user)->get(route('password.reset', [
        'token' => $this->token,
        'email' => $this->user->email,
    ]))->assertRedirectToRoute('links.index');
});

it('resets a user password', function () {
    $this->post(route('password.update'), [
        'token' => $this->token,
        'email' => $this->user->email,
        'password' => 'new-password',
    ])
        ->assertRedirectToRoute('login')
        ->assertSessionHas('status');

    $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
});

it('does not reset the password when the required fields are missing', function () {
    $this->post(route('password.update'))->assertInvalid([
        'token',
        'email',
        'password',
    ]);

    $this->assertNotTrue(Hash::check('new-password', $this->user->fresh()->password));
});

it('does not reset the password when the token is invalid', function () {
    $this->post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $this->user->email,
        'password' => 'new-password',
    ])->assertInvalid('email');

    $this->assertNotTrue(Hash::check('new-password', $this->user->fresh()->password));
});

it('does not reset the password when the email is invalid', function () {
    $this->post(route('password.update'), [
        'token' => $this->token,
        'email' => 'invalid-email',
        'password' => 'new-password',
    ])->assertInvalid('email');

    $this->assertNotTrue(Hash::check('new-password', $this->user->fresh()->password));

    $this->post(route('password.update'), [
        'token' => $this->token,
        'email' => 'incorrect-email@blst.to',
        'password' => 'new-password',
    ])->assertInvalid('email');

    $this->assertNotTrue(Hash::check('new-password', $this->user->fresh()->password));
});

it('does not reset the password when the password is invalid', function () {
    $this->post(route('password.update'), [
        'token' => $this->token,
        'email' => $this->user->email,
        'password' => 'short',
    ])->assertInvalid('password');

    $this->assertNotTrue(Hash::check('short', $this->user->fresh()->password));
});
