<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

it('requires the user to confirm their password', function () {
    $this->delete(route('current-user.destroy'))
        ->assertRedirectToRoute('password.confirm');

    $this->assertModelExists($this->user);
});

it('deletes the current user', function () {
    withoutPasswordConfirmation();

    $this->delete(route('current-user.destroy'))
        ->assertRedirect('/');

    $this->assertModelMissing($this->user);

    $this->assertGuest('web');
});

it('redirects unauthenticated users', function () {
    $this->post('logout');

    $this->delete(route('current-user.destroy'))
        ->assertRedirectToRoute('login');
});
