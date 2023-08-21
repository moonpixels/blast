<?php

use App\Domain\Team\Actions\CreateTeam;
use App\Domain\Team\Data\TeamData;

beforeEach(function () {
    $this->user = createUser();
});

it('creates a team for the given user', function () {
    $team = CreateTeam::run($this->user, TeamData::from([
        'name' => 'Test Team',
    ]));

    expect($team->name)->toBe('Test Team')
        ->and($team->owner->is($this->user))->toBeTrue()
        ->and($team->personal_team)->toBeFalse()
        ->and($this->user->currentTeam->is($team))->toBeTrue();
});

it('creates a personal team for the given user', function () {
    $team = CreateTeam::run($this->user, TeamData::from([
        'name' => 'Test Team',
    ]), true);

    expect($team->name)->toBe('Test Team')
        ->and($team->owner->is($this->user))->toBeTrue()
        ->and($team->personal_team)->toBeTrue();
});
