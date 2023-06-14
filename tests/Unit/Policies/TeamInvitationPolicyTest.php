<?php

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Policies\TeamInvitationPolicy;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->teamMember = User::factory()->create();
    $this->team->users()->attach($this->teamMember);

    $this->nonTeamMember = User::factory()->create();

    $this->teamInvitation = TeamInvitation::factory()->for($this->team)->create();

    $this->policy = new TeamInvitationPolicy();
});

it('allows owners to delete team invitations', function () {
    expect($this->policy->delete($this->user, $this->teamInvitation))->toBeTrue();
});

it('does not allow non-owners to delete team invitations', function () {
    expect($this->policy->delete($this->teamMember, $this->teamInvitation))->toBeFalse()
        ->and($this->policy->delete($this->nonTeamMember, $this->teamInvitation))->toBeFalse();
});

it('allows owners to resend team invitations', function () {
    expect($this->policy->resend($this->user, $this->teamInvitation))->toBeTrue();
});

it('does not allow non-owners to resend team invitations', function () {
    expect($this->policy->resend($this->teamMember, $this->teamInvitation))->toBeFalse()
        ->and($this->policy->resend($this->nonTeamMember, $this->teamInvitation))->toBeFalse();
});
