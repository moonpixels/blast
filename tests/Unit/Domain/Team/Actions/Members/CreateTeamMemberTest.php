<?php

use App\Domain\Team\Actions\Members\CreateTeamMember;

beforeEach(function () {
    $this->team = createTeam();
    $this->user = createUser();
});

it('can create a team membership', function () {
    expect(CreateTeamMember::run($this->team, $this->user))->toBeTrue()
        ->and($this->user->belongsToTeam($this->team))->toBeTrue();
});
