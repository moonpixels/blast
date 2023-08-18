<?php

use App\Domain\Team\Actions\DeleteTeam;
use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Mockery\MockInterface;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('deletes the team', function () {
    $this->delete(route('teams.destroy', $this->standardTeam))
        ->assertRedirectToRoute('links.index')
        ->assertSessionHas('success');

    $this->assertSoftDeleted($this->standardTeam);
});

it('only allows owners to delete the team', function () {
    $this->delete(route('teams.destroy', $this->membershipTeam))
        ->assertForbidden();

    $team = Team::factory()->create();

    $this->delete(route('teams.destroy', $team))
        ->assertForbidden();
});

it('does not delete personal teams', function () {
    $this->delete(route('teams.destroy', $this->user->personalTeam()))
        ->assertForbidden();
});

it('alerts the user if there was an error deleting the team', function () {
    $this->mock(DeleteTeam::class, function (MockInterface $mock) {
        $mock->shouldReceive('handle')->once()->andReturnFalse();
    });

    $this->delete(route('teams.destroy', $this->standardTeam))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertModelExists($this->standardTeam);
});
