<?php

use App\Domain\Team\Models\User;
use App\Domain\Team\Policies\TeamMembershipPolicy;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();
    $this->teamMembership = $this->user->teamMemberships()->where('team_id', $this->membershipTeam->id)->first();

    $this->nonTeamMember = User::factory()->create();

    $this->policy = new TeamMembershipPolicy();
});

it('allows owners to delete team memberships', function () {
    expect($this->policy->delete($this->membershipTeam->owner, $this->teamMembership))->toBeTrue();
});

it('allows the team member to delete their own team membership', function () {
    expect($this->policy->delete($this->user, $this->teamMembership))->toBeTrue();
});

it('does not allow other users to delete team memberships', function () {
    expect($this->policy->delete($this->nonTeamMember, $this->teamMembership))->toBeFalse();
});
