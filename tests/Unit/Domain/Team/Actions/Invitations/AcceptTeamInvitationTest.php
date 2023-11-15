<?php

use Domain\Team\Actions\Invitations\AcceptTeamInvitationAction;
use Domain\Team\Exceptions\InvalidTeamMemberException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->invitation = createTeamInvitation();
});

it('accepts a team invitation', function () {
    $user = createUser(attributes: ['email' => $this->invitation->email]);

    expect(AcceptTeamInvitationAction::run($this->invitation))->toBeTrue()
        ->and($this->invitation)->toBeDeleted()
        ->and($user->belongsToTeam($this->invitation->team))->toBeTrue()
        ->and($user->fresh()->currentTeam->is($this->invitation->team))->toBeTrue();
});

it('throws an exception if the user is already a member', function () {
    createUser(attributes: ['email' => $this->invitation->email])
        ->teams()
        ->attach($this->invitation->team);

    try {
        AcceptTeamInvitationAction::run($this->invitation);
    } catch (InvalidTeamMemberException $e) {
        expect($this->invitation)->toBeDeleted();
        throw $e;
    }
})->throws(InvalidTeamMemberException::class);

it('throws an exception if the user does not exist', function () {
    AcceptTeamInvitationAction::run($this->invitation);
})->throws(ModelNotFoundException::class);
