<?php

use App\Domain\Team\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->unverified()->create();
    $this->actingAs($this->user);
});

it('shows the verify email page', function () {
    $this->get(route('verification.notice'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/VerifyEmail')
            ->where('email', $this->user->email)
        );
});

it('can resend the verification email', function () {
    Notification::fake();

    $this->post(route('verification.send'))
        ->assertRedirect();

    $this->get(route('verification.notice'))
        ->assertSessionHas('success', [
            'title' => 'Email verification resent',
            'message' => 'A new email verification link has been sent to '.$this->user->email,
        ]);

    Notification::assertSentTo($this->user, VerifyEmail::class);
});
