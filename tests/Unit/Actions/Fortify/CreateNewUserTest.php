<?php

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    $this->action = new CreateNewUser();

    $this->userData = User::factory()->make()->only(['name', 'email', 'password']);
});

it('does not allow registration when disabled', function () {
    disableRegistration();

    $this->action->create($this->userData);
})->throws(HttpException::class);

it('creates a new user', function () {
    $user = $this->action->create($this->userData);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->toArray())->toMatchArray([
            'name' => $this->userData['name'],
            'email' => $this->userData['email'],
        ]);
});

it('stores a hashed version of the users password', function () {
    $user = $this->action->create($this->userData);

    expect($user->password)->not()->toBe($this->userData['password'])
        ->and(Hash::check($this->userData['password'], $user->password))->toBeTrue();
});

it('does not create a user when the required fields are missing', function () {
    $result = $this->action->create([]);

    expect($result)->toHaveKeys(['name', 'email', 'password'])
        ->and(User::count())->toBe(0);
})->throws(ValidationException::class);

it('does not create a user when the email is already taken', function () {
    User::factory()->create(['email' => $this->userData['email']]);

    $result = $this->action->create($this->userData);

    expect($result)->toHaveKey('email')
        ->and(User::count())->toBe(0);
})->throws(ValidationException::class);

it('does not create a user when the password is too short', function () {
    $this->userData['password'] = fake()->password(1, 7);

    $result = $this->action->create($this->userData);

    expect($result)->toHaveKey('password')
        ->and(User::count())->toBe(0);
})->throws(ValidationException::class);
