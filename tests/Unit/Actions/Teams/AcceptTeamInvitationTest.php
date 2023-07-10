<?php

use App\Actions\Teams\AcceptTeamInvitation;
use App\Exceptions\InvalidTeamMembershipException;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

    $this->invitedUser = User::factory()->create();

    $this->teamInvitation = TeamInvitation::factory()->for($this->team)->create([
        'email' => $this->invitedUser->email,
    ]);
});

it('can accept a team invitation', function () {
    expect(AcceptTeamInvitation::run($this->teamInvitation))->toBeTrue()
        ->and($this->invitedUser->belongsToTeam($this->team))->toBeTrue()
        ->and($this->team->id)->toEqual($this->invitedUser->fresh()->current_team_id);

    $this->assertModelMissing($this->teamInvitation);
});

it('throws an exception when accepting an invitation if the user is already on the team', function () {
    $this->invitedUser->teams()->attach($this->team);

    try {
        AcceptTeamInvitation::run($this->teamInvitation);
    } catch (InvalidTeamMembershipException $e) {
        $this->assertModelMissing($this->teamInvitation);
        throw $e;
    }
})->throws(InvalidTeamMembershipException::class);

it('throws an exception when accepting an invitation if the user does not exist', function () {
    $this->teamInvitation->update(['email' => 'invalid-email@blst.to']);

    AcceptTeamInvitation::run($this->teamInvitation);
})->throws(ModelNotFoundException::class);
