<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('allows owners to update the team', function () {
    $this->put(route('teams.update', $this->standardTeam), [
        'name' => 'Test Team',
    ])->assertRedirect()->assertSessionHas('success');

    $this->assertDatabaseHas('teams', [
        'id' => $this->standardTeam->id,
        'name' => 'Test Team',
    ]);
});

it('does not allow non-owners to update the team', function () {
    $this->put(route('teams.update', $this->membershipTeam), [
        'name' => 'Test Team',
    ])->assertForbidden();

    $team = Team::factory()->create();

    $this->put(route('teams.update', $team), [
        'name' => 'Test Team',
    ])->assertForbidden();

    $this->post(route('logout'));

    $this->put(route('teams.update', $this->membershipTeam), [
        'name' => 'Test Team',
    ])->assertForbidden();

    $this->assertDatabaseMissing('teams', [
        'name' => 'Test Team',
    ]);
});
