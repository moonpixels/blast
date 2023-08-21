<?php

use App\Domain\User\Actions\CreateUser;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    $this->action = new CreateUser;
});

it('creates a new user', function () {
    $user = $this->action->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($user)->toExistInDatabase()
        ->and($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->currentTeam->name)->toBe('Personal Team');
});

it('does not create a new user if registration is disabled', function () {
    config(['blast.disable_registration' => true]);

    $this->action->create([]);
})->throws(HttpException::class, 'Registration is currently disabled');
