<?php

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

test('users can register', function () {
    Notification::fake();

    $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $user = auth()->user();

    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->currentTeam->name)->toBe('Personal Team');

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('registration fails with duplicate email', function () {
    createUser();

    $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ])->assertInvalid(['email']);
});

test('users can login', function () {
    $user = createUser();

    $this->post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'password',
        'remember' => true,
    ])->assertRedirectToRoute('links.index');

    expect(auth()->user()->is($user))->toBeTrue()
        ->and($user->fresh()->remember_token)->not()->toBeNull();
});

test('login fails with incorrect password', function () {
    createUser();

    $this->post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ])->assertInvalid(['email']);

    expect(auth()->check())->toBeFalse();
});

test('users can login with two-factor authentication', function () {
    $user = createUser(states: ['withTwoFactorAuthentication']);

    $this->post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'password',
    ])->assertRedirectToRoute('two-factor.login');

    $this->post(route('two-factor.login'), [
        'code' => 'wrong-code',
    ])->assertInvalid(['code']);

    $tfaEngine = app(Google2FA::class);
    $code = $tfaEngine->getCurrentOtp(Crypt::decrypt($user->two_factor_secret));

    $this->post(route('two-factor.login'), [
        'code' => $code,
    ])->assertRedirectToRoute('links.index');

    expect(auth()->user()->is($user))->toBeTrue();
});

test('login fails with incorrect two-factor authentication code', function () {
    createUser(states: ['withTwoFactorAuthentication']);

    $this->post(route('login'), [
        'email' => 'test@example.com',
        'password' => 'password',
    ])->assertRedirectToRoute('two-factor.login');

    $this->post(route('two-factor.login'), [
        'code' => 'wrong-code',
    ])->assertInvalid(['code']);

    expect(auth()->check())->toBeFalse();
});

test('users can log out', function () {
    login();

    $this->post(route('logout'));

    expect(auth()->check())->toBeFalse();
});

test('users can request a password reset', function () {
    Notification::fake();

    $user = createUser();

    $this->post(route('password.email'), [
        'email' => 'test@example.com',
    ]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('users can reset their password', function () {
    $user = createUser();

    $token = app(PasswordBroker::class)->createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'test@example.com',
        'password' => 'new-password',
    ])->assertRedirectToRoute('login');

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('users can confirm their password', function () {
    login();

    $this->post(route('password.confirm'), [
        'password' => 'password',
    ]);

    expect(session()->has('auth.password_confirmed_at'))->toBeTrue();
});
