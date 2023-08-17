]<?php

use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Models\User;
use App\Domain\Team\Policies\TeamInvitationPolicy;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->standardTeamInvitation = TeamInvitation::factory()->for($this->standardTeam)->create();
    $this->membershipTeamInvitation = TeamInvitation::factory()->for($this->membershipTeam)->create();

    $this->nonTeamMember = User::factory()->create();

    $this->policy = new TeamInvitationPolicy();
});

it('allows owners to delete team invitations', function () {
    expect($this->policy->delete($this->user, $this->standardTeamInvitation))->toBeTrue();
});

it('does not allow non-owners to delete team invitations', function () {
    expect($this->policy->delete($this->user, $this->membershipTeamInvitation))->toBeFalse()
        ->and($this->policy->delete($this->nonTeamMember, $this->standardTeamInvitation))->toBeFalse();
});

it('allows owners to resend team invitations', function () {
    expect($this->policy->resend($this->user, $this->standardTeamInvitation))->toBeTrue();
});

it('does not allow non-owners to resend team invitations', function () {
    expect($this->policy->resend($this->user, $this->membershipTeamInvitation))->toBeFalse()
        ->and($this->policy->resend($this->nonTeamMember, $this->standardTeamInvitation))->toBeFalse();
});
