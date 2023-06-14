<?php

use App\Models\Team;
use App\Models\User;
use App\Services\TeamMembershipService;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->team->users()->attach(User::factory()->create());
    $this->teamMember = $this->team->users->first();

    $this->teamMembershipService = app(TeamMembershipService::class);
});

it('can delete a team membership', function () {
    $this->assertTrue($this->teamMembershipService->deleteTeamMembership($this->teamMember->team_membership));

    $this->assertFalse($this->teamMember->belongsToTeam($this->team));
});

it('switches the users current team to their personal team when deleting a team membership', function () {
    $this->teamMember->switchTeam($this->team);

    $this->assertTrue($this->teamMembershipService->deleteTeamMembership($this->teamMember->team_membership));

    $this->assertFalse($this->teamMember->belongsToTeam($this->team));

    $this->assertEquals($this->teamMember->fresh()->current_team_id, $this->teamMember->personalTeam()->id);
});
