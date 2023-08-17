<?php

use App\Domain\Team\Actions\UpdateTeam;
use App\Domain\Team\Data\TeamData;
use App\Domain\Team\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();
});

it('can update a team', function () {
    expect(UpdateTeam::run($this->team, TeamData::from(['name' => 'Test Team Updated'])))->toBeTrue()
        ->and($this->team->fresh()->name)->toBe('Test Team Updated');
});
