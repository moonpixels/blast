<?php

use App\Domain\Team\Policies\TeamPolicy;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->nonTeamMember = User::factory()->create();

    $this->policy = new TeamPolicy();
});

it('allows owners to view teams', function () {
    expect($this->policy->view($this->user, $this->standardTeam))->toBeTrue();
});

it('allows team members to view teams', function () {
    expect($this->policy->view($this->user, $this->membershipTeam))->toBeTrue();
});

it('allows owners to update teams', function () {
    expect($this->policy->update($this->user, $this->standardTeam))->toBeTrue();
});

it('allows owners to delete standard teams', function () {
    expect($this->policy->delete($this->user, $this->standardTeam))->toBeTrue();
});

it('does not allow owners to delete personal teams', function () {
    expect($this->policy->delete($this->user, $this->user->personalTeam()))->toBeFalse();
});

it('allows owners to create team members', function () {
    expect($this->policy->createMember($this->user, $this->standardTeam))->toBeTrue();
});

it('does not allow owners to create team members for personal teams', function () {
    expect($this->policy->createMember($this->user, $this->user->personalTeam()))->toBeFalse();
});

it('does not allow users without membership to view the team', function () {
    expect($this->policy->view($this->nonTeamMember, $this->standardTeam))->toBeFalse();
});

it('does not allow non-owners to update teams', function () {
    expect($this->policy->update($this->user, $this->membershipTeam))->toBeFalse()
        ->and($this->policy->update($this->nonTeamMember, $this->standardTeam))->toBeFalse();
});

it('does not allow non-owners to delete teams', function () {
    expect($this->policy->delete($this->user, $this->membershipTeam))->toBeFalse()
        ->and($this->policy->delete($this->nonTeamMember, $this->standardTeam))->toBeFalse();
});

it('does not allow non-owners to create team members', function () {
    expect($this->policy->createMember($this->user, $this->membershipTeam))->toBeFalse()
        ->and($this->policy->createMember($this->nonTeamMember, $this->standardTeam))->toBeFalse();
});
