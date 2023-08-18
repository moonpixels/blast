<?php

use App\Domain\Team\Actions\Invitations\DeleteTeamInvitation;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->notPersonal()->first();

    $this->invitedUser = User::factory()->create();

    $this->teamInvitation = TeamInvitation::factory()->for($this->team)->create([
        'email' => $this->invitedUser->email,
    ]);
});

it('can cancel a team invitation', function () {
    expect(DeleteTeamInvitation::run($this->teamInvitation))->toBeTrue();

    $this->assertModelMissing($this->teamInvitation);
});
