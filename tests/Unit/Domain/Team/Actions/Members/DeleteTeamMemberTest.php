<?php

use App\Domain\Team\Actions\Members\DeleteTeamMember;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();
});

it('can delete a team membership', function () {
    expect(DeleteTeamMember::run($this->membershipTeam, $this->user))->toBeTrue()
        ->and($this->user->belongsToTeam($this->membershipTeam))->toBeFalse();
});

it('switches the users current team to their personal team when deleting a team membership', function () {
    $this->user->switchTeam($this->membershipTeam);

    expect(DeleteTeamMember::run($this->membershipTeam, $this->user))->toBeTrue()
        ->and($this->user->currentTeam->id)->toEqual($this->user->personalTeam()->id);
});
