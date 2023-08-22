<?php

use App\Domain\Team\Actions\Members\DeleteTeamMember;

beforeEach(function () {
    $this->user = createUser();
    $this->memberTeam = getTeamForUser($this->user, 'Member Team');

    $this->user->switchTeam($this->memberTeam);
});

it('deletes a team member', function () {
    expect(DeleteTeamMember::run($this->memberTeam, $this->user))->toBeTrue()
        ->and($this->user->belongsToTeam($this->memberTeam))->toBeFalse()
        ->and($this->user->currentTeam->is($this->user->personalTeam()))->toBeTrue();
});
