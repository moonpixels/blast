<?php

use App\Domain\Team\Actions\Users\CreateNewUser;
use App\Domain\Team\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    $this->action = app(CreateNewUser::class);

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
        ])
        ->and($user->password)->not()->toBe($this->userData['password'])
        ->and(Hash::check($this->userData['password'], $user->password))->toBeTrue()
        ->and($user->personalTeam()->exists())->toBeTrue();
});

it('does not create a user when the required fields are missing', function () {
    $this->action->create([]);
})->throws(ValidationException::class);

it('does not create a user when the email is already taken', function () {
    User::factory()->create(['email' => $this->userData['email']]);

    $this->action->create($this->userData);
})->throws(ValidationException::class);

it('does not create a user when the password is too short', function () {
    $this->userData['password'] = 'short';

    $this->action->create($this->userData);
})->throws(ValidationException::class);
