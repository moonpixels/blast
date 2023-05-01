<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('show the forgot password page', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/ForgotPassword')
            ->where('status', null)
        );
});

it('does not show the forgot password page when authenticated', function () {
    $this->actingAs($this->user)->get(route('password.request'))->assertRedirect(route('dashboard'));
});

it('sends a forgot password email', function () {
    Notification::fake();

    $this->get(route('password.request'))->assertOk();

    $this->post(route('password.request'), ['email' => $this->user->email])
        ->assertRedirect('/forgot-password')
        ->assertSessionHas('status', __('passwords.sent'));

    $token = DB::table('password_reset_tokens')->first()->token;

    Notification::assertSentTo($this->user, ResetPassword::class, function ($notification) use ($token) {
        return Hash::check($notification->token, $token);
    });
});

it('does not send an email when the required fields are missing', function () {
    Notification::fake();

    $this->post(route('password.request'))->assertInvalid(['email']);

    Notification::assertNothingSent();
});

it('does not send an email when the email is not valid', function () {
    Notification::fake();

    $this->post(route('password.request'), [
        'email' => 'not-an-email',
    ])->assertInvalid(['email']);

    Notification::assertNothingSent();
});

it('does not send and email when the user does not exist', function () {
    Notification::fake();

    $this->post(route('password.request'), [
        'email' => 'not-a-user@example.com',
    ])->assertInvalid(['email']);

    Notification::assertNothingSent();
});
