<?php

use App\Domain\Team\Actions\Invitations\AcceptTeamInvitation;
use App\Domain\Team\Exceptions\InvalidTeamMemberException;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->notPersonal()->first();

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

    $this->assertDatabaseHas('team_user', [
        'user_id' => $this->invitedUser->id,
        'team_id' => $this->team->id,
    ]);
});

it('throws an exception when accepting an invitation if the user is already on the team', function () {
    $this->invitedUser->teams()->attach($this->team);

    try {
        AcceptTeamInvitation::run($this->teamInvitation);
    } catch (InvalidTeamMemberException $e) {
        $this->assertModelMissing($this->teamInvitation);
        throw $e;
    }
})->throws(InvalidTeamMemberException::class);

it('throws an exception when accepting an invitation if the user does not exist', function () {
    $this->teamInvitation->update(['email' => 'invalid-email@blst.to']);

    AcceptTeamInvitation::run($this->teamInvitation);
})->throws(ModelNotFoundException::class);
