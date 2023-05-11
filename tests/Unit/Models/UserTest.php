<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()
        ->withTwoFactorAuthentication()
        ->create([
            'name' => 'John Doe',
        ]);
});

it('generates a two-factor QR code SVG for the user', function () {
    $this->assertStringStartsWith(
        '<svg',
        $this->user->twoFactorQrCodeSvg()
    );
});

it('gives the status of two-factor authentication', function () {
    expect($this->user->two_factor_enabled)->toBeTrue();

    $this->user->two_factor_confirmed_at = null;

    expect($this->user->two_factor_enabled)->toBeFalse();
});

it('gives the user\'s initials', function () {
    expect($this->user->initials)->toBe('JD');
});
