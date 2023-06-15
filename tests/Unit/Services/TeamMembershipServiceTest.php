<?php

use App\Models\User;
use App\Services\TeamMembershipService;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->teamMembershipService = app(TeamMembershipService::class);
});

it('can delete a team membership', function () {
    $this->assertTrue($this->teamMembershipService->deleteTeamMembership($this->membershipTeam->team_membership));

    $this->assertFalse($this->user->belongsToTeam($this->membershipTeam));
});

it('switches the users current team to their personal team when deleting a team membership', function () {
    $this->user->switchTeam($this->membershipTeam);

    $this->assertTrue($this->teamMembershipService->deleteTeamMembership($this->membershipTeam->team_membership));

    $this->assertFalse($this->user->belongsToTeam($this->membershipTeam));

    $this->assertEquals($this->user->fresh()->current_team_id, $this->user->personalTeam()->id);
});
