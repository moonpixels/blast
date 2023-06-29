<?php

use App\Actions\Teams\DeleteTeam;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()
        ->withStandardTeam()
        ->withTeamMembership()
        ->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();
});

it('can delete a team', function () {
    DeleteTeam::execute($this->standardTeam);

    $this->assertModelMissing($this->standardTeam);
});

it('cannot delete a personal team', function () {
    $this->assertFalse(DeleteTeam::execute($this->user->personalTeam()));

    $this->assertModelExists($this->user->personalTeam());
});

it('can delete a team and reassign the users and owner to their personal teams', function () {
    $this->membershipTeam->owner->switchTeam($this->membershipTeam);
    $this->user->switchTeam($this->membershipTeam);

    $this->assertTrue(DeleteTeam::execute($this->membershipTeam));

    $this->assertModelMissing($this->membershipTeam);

    expect($this->user->fresh()->currentTeam->id)->toBe($this->user->personalTeam()->id)
        ->and($this->membershipTeam->owner->currentTeam->id)->toBe($this->membershipTeam->owner->personalTeam()->id);
});
