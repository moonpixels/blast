<?php

use App\Domain\Link\Models\Link;
use App\Domain\Link\Policies\LinkPolicy;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->standardTeamLink = Link::factory()->for($this->standardTeam)->create();
    $this->membershipTeamLink = Link::factory()->for($this->membershipTeam)->create();

    $this->nonTeamMember = User::factory()->create();

    $this->policy = new LinkPolicy();
});

it('allows owners to update links', function () {
    expect($this->policy->update($this->user, $this->standardTeamLink))->toBeTrue();
});

it('allows team members to update links', function () {
    expect($this->policy->update($this->user, $this->membershipTeamLink))->toBeTrue();
});

it('does not allow users who are not members of the team to update links', function () {
    expect($this->policy->update($this->nonTeamMember, $this->standardTeamLink))->toBeFalse();
});

it('allows owners to delete links', function () {
    expect($this->policy->delete($this->user, $this->standardTeamLink))->toBeTrue();
});

it('allows team members to delete links', function () {
    expect($this->policy->delete($this->user, $this->membershipTeamLink))->toBeTrue();
});

it('does not allow users who are not members of the team to delete links', function () {
    expect($this->policy->delete($this->nonTeamMember, $this->standardTeamLink))->toBeFalse();
});

it('allows owners to restore links', function () {
    $this->standardTeamLink->delete();

    expect($this->policy->restore($this->user, $this->standardTeamLink))->toBeTrue();
});

it('allows team members to restore links', function () {
    $this->membershipTeamLink->delete();

    expect($this->policy->restore($this->user, $this->membershipTeamLink))->toBeTrue();
});

it('does not allow users who are not members of the team to restore links', function () {
    $this->standardTeamLink->delete();

    expect($this->policy->restore($this->nonTeamMember, $this->standardTeamLink))->toBeFalse();
});
