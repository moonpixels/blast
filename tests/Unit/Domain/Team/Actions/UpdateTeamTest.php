<?php

use Domain\Team\Actions\UpdateTeamAction;
use Domain\Team\DTOs\TeamData;

beforeEach(function () {
    $this->team = createTeam();
});

it('updates a team', function () {
    expect(UpdateTeamAction::run($this->team, TeamData::from(['name' => 'Test Team'])))->toBeTrue()
        ->and($this->team->name)->toBe('Test Team');
});
