<?php

use Domain\Team\Actions\Members\CreateTeamMemberAction;

beforeEach(function () {
    $this->team = createTeam();
    $this->user = createUser();
});

it('can create a team membership', function () {
    expect(CreateTeamMemberAction::run($this->team, $this->user))->toBeTrue()
        ->and($this->user->belongsToTeam($this->team))->toBeTrue();
});
