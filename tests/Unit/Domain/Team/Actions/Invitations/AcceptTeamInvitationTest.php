<?php

use App\Domain\Team\Actions\Invitations\AcceptTeamInvitation;
use App\Domain\Team\Exceptions\InvalidTeamMemberException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->invitation = createTeamInvitation();
});

it('accepts a team invitation', function () {
    $user = createUser(attributes: ['email' => $this->invitation->email]);

    expect(AcceptTeamInvitation::run($this->invitation))->toBeTrue()
        ->and($this->invitation)->toBeDeleted()
        ->and($user->belongsToTeam($this->invitation->team))->toBeTrue()
        ->and($user->fresh()->currentTeam->is($this->invitation->team))->toBeTrue();
});

it('throws an exception if the user is already a member', function () {
    createUser(attributes: ['email' => $this->invitation->email])
        ->teams()
        ->attach($this->invitation->team);

    try {
        AcceptTeamInvitation::run($this->invitation);
    } catch (InvalidTeamMemberException $e) {
        expect($this->invitation)->toBeDeleted();
        throw $e;
    }
})->throws(InvalidTeamMemberException::class);

it('throws an exception if the user does not exist', function () {
    AcceptTeamInvitation::run($this->invitation);
})->throws(ModelNotFoundException::class);
