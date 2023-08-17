<?php

use App\Domain\Team\Actions\Users\DeleteUser;
use App\Domain\Team\Models\User;
use Mockery\MockInterface;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

it('requires the user to confirm their password', function () {
    $this->delete(route('user.destroy'))
        ->assertRedirectToRoute('password.confirm');

    $this->assertModelExists($this->user);
});

it('deletes the current user', function () {
    withoutPasswordConfirmation();

    $this->delete(route('user.destroy'))
        ->assertRedirect('/');

    $this->assertModelMissing($this->user);

    $this->assertGuest('web');
});

it('redirects unauthenticated users', function () {
    $this->post('logout');

    $this->delete(route('user.destroy'))
        ->assertRedirectToRoute('login');
});

it('alerts the user if there was an error deleting their account', function () {
    withoutPasswordConfirmation();

    $this->mock(DeleteUser::class, function (MockInterface $mock) {
        $mock->shouldReceive('handle')->once()->andReturnFalse();
    });

    $this->delete(route('user.destroy'))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertAuthenticatedAs($this->user);
});
