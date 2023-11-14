<?php

use Domain\Team\Actions\CreateTeamAction;
use Domain\Team\DTOs\TeamData;

beforeEach(function () {
    $this->user = createUser();
});

it('creates a team for the given user', function () {
    $team = CreateTeamAction::run($this->user, TeamData::from([
        'name' => 'Test Team',
    ]));

    expect($team->name)->toBe('Test Team')
        ->and($team->owner->is($this->user))->toBeTrue()
        ->and($team->personal_team)->toBeFalse()
        ->and($this->user->currentTeam->is($team))->toBeTrue();
});

it('creates a personal team for the given user', function () {
    $team = CreateTeamAction::run($this->user, TeamData::from([
        'name' => 'Test Team',
        'personal_team' => true,
    ]));

    expect($team->name)->toBe('Test Team')
        ->and($team->owner->is($this->user))->toBeTrue()
        ->and($team->personal_team)->toBeTrue();
});
