<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

it('updates the users password', function () {
    $this->put(route('user-password.update'), [
        'current_password' => 'password',
        'password' => 'new-password',
    ])->assertRedirect()->assertSessionHas('success');

    $this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
});

it('does not update the users password when the current password is invalid', function () {
    $this->put(route('user-password.update'), [
        'current_password' => 'wrong-password',
        'password' => 'new-password',
    ])->assertRedirect()->assertInvalid('current_password');

    $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
});

it('does not update the users password when the new password is invalid', function () {
    $this->put(route('user-password.update'), [
        'current_password' => 'password',
        'password' => 'short',
    ])->assertRedirect()->assertInvalid('password');

    $this->put(route('user-password.update'), [
        'current_password' => 'password',
        'password' => '',
    ])->assertRedirect()->assertInvalid('password');

    $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
});
