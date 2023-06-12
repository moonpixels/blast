<?php

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Policies\TeamInvitationPolicy;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->memberUser = User::factory()->create();
    $this->team->users()->attach($this->memberUser);

    $this->nonMemberUser = User::factory()->create();

    $this->teamInvitation = TeamInvitation::factory()->for($this->team)->create();

    $this->policy = new TeamInvitationPolicy();
});

it('allows owners to delete team invitations', function () {
    expect($this->policy->delete($this->user, $this->teamInvitation))->toBeTrue();
});

it('does not allow non-owners to delete team invitations', function () {
    expect($this->policy->delete($this->memberUser, $this->teamInvitation))->toBeFalse()
        ->and($this->policy->delete($this->nonMemberUser, $this->teamInvitation))->toBeFalse();
});

it('allows owners to resend team invitations', function () {
    expect($this->policy->resend($this->user, $this->teamInvitation))->toBeTrue();
});

it('does not allow non-owners to resend team invitations', function () {
    expect($this->policy->resend($this->memberUser, $this->teamInvitation))->toBeFalse()
        ->and($this->policy->resend($this->nonMemberUser, $this->teamInvitation))->toBeFalse();
});
