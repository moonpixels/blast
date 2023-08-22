<?php

use App\Domain\Team\Actions\UpdateTeam;
use App\Domain\Team\Data\TeamData;

beforeEach(function () {
    $this->team = createTeam();
});

it('updates a team', function () {
    expect(UpdateTeam::run($this->team, TeamData::from(['name' => 'Test Team'])))->toBeTrue()
        ->and($this->team->name)->toBe('Test Team');
});
