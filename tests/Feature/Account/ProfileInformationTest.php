<?php

use App\Domain\Team\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

it('updates the users name', function () {
    $this->put(route('user-profile-information.update'), [
        'name' => 'Updated Name',
        'email' => $this->user->email,
    ])->assertRedirect()->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => 'Updated Name',
        'email' => $this->user->email,
    ]);
});

it('updates the users email', function () {
    $this->put(route('user-profile-information.update'), [
        'name' => $this->user->name,
        'email' => 'updated.email@blst.to',
    ])->assertRedirect()->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'email' => 'updated.email@blst.to',
    ]);
});

it('does not update the users email when the email is already in use', function () {
    $user = User::factory()->create();

    $this->put(route('user-profile-information.update'), [
        'name' => $this->user->name,
        'email' => $user->email,
    ])->assertRedirect()->assertInvalid('email');

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'email' => $this->user->email,
    ]);
});
