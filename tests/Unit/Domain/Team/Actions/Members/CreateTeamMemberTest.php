<?php

use App\Domain\Team\Actions\Members\CreateTeamMember;
use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create();
});

it('can create a team membership', function () {
    expect(CreateTeamMember::run($this->team, $this->user))->toBeTrue()
        ->and($this->user->belongsToTeam($this->team))->toBeTrue();
});
