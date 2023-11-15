<?php

use Domain\Team\Policies\TeamInvitationPolicy;

beforeEach(function () {
    $this->policy = new TeamInvitationPolicy();

    $this->owner = createUser(attributes: ['email' => 'owner@example.com']);

    $this->invitee = createUser(attributes: ['email' => 'invitee@example.com']);

    $this->user = createUser();

    $this->invitation = createTeamInvitation(attributes: [
        'team_id' => getTeamForUser($this->owner, 'Owned Team')->id,
        'email' => $this->invitee->email,
    ]);
});

it('only lets the owner or invitee delete the invitation', function () {
    expect($this->policy->delete($this->owner, $this->invitation))->toBeTrue()
        ->and($this->policy->delete($this->invitee, $this->invitation))->toBeTrue()
        ->and($this->policy->delete($this->user, $this->invitation))->toBeFalse();
});

it('only lets the owner resend the invitation', function () {
    expect($this->policy->resend($this->owner, $this->invitation))->toBeTrue()
        ->and($this->policy->resend($this->invitee, $this->invitation))->toBeFalse()
        ->and($this->policy->resend($this->user, $this->invitation))->toBeFalse();
});

it('only lets the invitee accept the invitation', function () {
    expect($this->policy->accept($this->owner, $this->invitation))->toBeFalse()
        ->and($this->policy->accept($this->invitee, $this->invitation))->toBeTrue()
        ->and($this->policy->accept($this->user, $this->invitation))->toBeFalse();
});
