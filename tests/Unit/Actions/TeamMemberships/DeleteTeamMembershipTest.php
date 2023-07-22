<?php

use App\Actions\TeamMemberships\DeleteTeamMembership;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();
    $this->teamMembership = $this->user->teamMemberships()->first();
});

it('can delete a team membership', function () {
    $this->assertTrue(DeleteTeamMembership::run($this->teamMembership));

    $this->assertFalse($this->user->belongsToTeam($this->membershipTeam));
});

it('switches the users current team to their personal team when deleting a team membership', function () {
    $this->user->switchTeam($this->membershipTeam);

    $this->assertTrue(DeleteTeamMembership::run($this->teamMembership->fresh()));

    $this->assertFalse($this->user->belongsToTeam($this->membershipTeam));

    $this->assertEquals($this->user->fresh()->currentTeam->id, $this->user->personalTeam()->id);
});
