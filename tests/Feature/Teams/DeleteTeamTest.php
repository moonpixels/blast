<?php

use App\Actions\Teams\DeleteTeam;
use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;
use Mockery\MockInterface;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();
    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

    $this->actingAs($this->user);
});

it('deletes the team', function () {
    $this->delete(route('teams.destroy', $this->team))
        ->assertRedirectToRoute('links.index')
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('teams', [
        'id' => $this->team->id,
    ]);
});

it('only allows owners to delete the team', function () {
    $team = Team::factory()->create();

    $this->delete(route('teams.destroy', $team))
        ->assertForbidden();

    TeamMembership::factory()->for($this->user)->for($team)->create();

    $this->delete(route('teams.destroy', $team))
        ->assertForbidden();
});

it('does not delete personal teams', function () {
    $this->team->update(['personal_team' => true]);

    $this->delete(route('teams.destroy', $this->team))
        ->assertForbidden();
});

it('alerts the user if there was an error deleting the team', function () {
    $this->mock(DeleteTeam::class, function (MockInterface $mock) {
        $mock->shouldReceive('handle')->once()->andReturnFalse();
    });

    $this->delete(route('teams.destroy', $this->team))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertModelExists($this->team);
});
