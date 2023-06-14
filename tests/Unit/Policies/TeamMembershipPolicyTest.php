<?php

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamMembershipPolicy;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->team->users()->attach(User::factory()->create());
    $this->teamMember = $this->team->users()->first();

    $this->nonTeamMember = User::factory()->create();

    $this->policy = new TeamMembershipPolicy();
});

it('allows owners to delete team memberships', function () {
    expect($this->policy->delete($this->user, $this->teamMember->team_membership))->toBeTrue();
});

it('allows the team member to delete their own team membership', function () {
    expect($this->policy->delete($this->teamMember, $this->teamMember->team_membership))->toBeTrue();
});

it('does not allow other users to delete team memberships', function () {
    expect($this->policy->delete($this->nonTeamMember, $this->teamMember->team_membership))->toBeFalse();
});
