<?php

use App\Actions\Teams\CancelTeamInvitation;
use App\Models\TeamInvitation;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

    $this->invitedUser = User::factory()->create();

    $this->teamInvitation = TeamInvitation::factory()->for($this->team)->create([
        'email' => $this->invitedUser->email,
    ]);
});

it('can cancel a team invitation', function () {
    expect(CancelTeamInvitation::execute($this->teamInvitation))->toBeTrue();

    $this->assertModelMissing($this->teamInvitation);
});
