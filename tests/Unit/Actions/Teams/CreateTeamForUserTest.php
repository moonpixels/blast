<?php

use App\Actions\Teams\CreateTeamForUser;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();
});

it('can create a standard team for a user', function () {
    $team = CreateTeamForUser::run($this->user, ['name' => 'Test Team']);

    expect($team->name)->toBe('Test Team')
        ->and($team->personal_team)->toBeFalse()
        ->and($team->owner->id)->toBe($this->user->id)
        ->and($this->user->fresh()->current_team_id)->toBe($team->id);

    $this->assertDatabaseHas('teams', [
        'name' => 'Test Team',
        'personal_team' => false,
        'owner_id' => $this->user->id,
    ]);

    expect($this->user->current_team_id)->toBe($team->id);
});

it('can create a personal team for a user', function () {
    $team = CreateTeamForUser::run($this->user, ['name' => 'Test Team', 'personal_team' => true]);

    expect($team->name)->toBe('Test Team')
        ->and($team->personal_team)->toBeTrue()
        ->and($team->owner->id)->toBe($this->user->id)
        ->and($this->user->fresh()->current_team_id)->toBe($team->id);

    $this->assertDatabaseHas('teams', [
        'name' => 'Test Team',
        'personal_team' => true,
        'owner_id' => $this->user->id,
    ]);

    expect($this->user->current_team_id)->toBe($team->id);
});
