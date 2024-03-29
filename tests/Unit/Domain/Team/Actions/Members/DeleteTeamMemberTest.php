<?php

use Domain\Team\Actions\Members\DeleteTeamMemberAction;

beforeEach(function () {
    $this->user = createUser();
    $this->memberTeam = getTeamForUser($this->user, 'Member Team');

    $this->user->switchTeam($this->memberTeam);
});

it('deletes a team member', function () {
    expect(DeleteTeamMemberAction::run($this->memberTeam, $this->user))->toBeTrue()
        ->and($this->user->belongsToTeam($this->memberTeam))->toBeFalse()
        ->and($this->user->currentTeam->is($this->user->personalTeam()))->toBeTrue();
});
