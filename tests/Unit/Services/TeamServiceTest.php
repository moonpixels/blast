<?php

use App\Models\User;
use App\Services\TeamService;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

    $this->teamService = app(TeamService::class);
});

it('can create a standard team for a user', function () {
    $team = $this->teamService->createTeamForUser($this->user, [
        'name' => 'Test Team',
    ]);

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
    $team = $this->teamService->createTeamForUser($this->user, [
        'name' => 'Test Team',
        'personal_team' => true,
    ]);

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

it('can update a team', function () {
    $this->teamService->updateTeam($this->team, [
        'name' => 'Test Team Updated',
    ]);

    expect($this->team->fresh()->name)->toBe('Test Team Updated');
});

it('can delete a team', function () {
    $this->teamService->deleteTeam($this->team);

    $this->assertDatabaseMissing('teams', [
        'id' => $this->team->id,
    ]);
});

it('cannot delete a personal team', function () {
    $this->team->update([
        'personal_team' => true,
    ]);

    $this->assertFalse($this->teamService->deleteTeam($this->team));

    $this->assertDatabaseHas('teams', [
        'id' => $this->team->id,
    ]);
});

it('can delete a team and reassign the users and owner to their personal teams', function () {
    $this->user->switchTeam($this->team);

    $user1 = User::factory()->create();
    $this->team->users()->attach($user1);
    $user1->switchTeam($this->team);

    $this->assertTrue($this->teamService->deleteTeam($this->team));

    $this->assertModelMissing($this->team);

    expect($user1->fresh()->current_team_id)->toBe($user1->personalTeam()->id)
        ->and($this->user->fresh()->current_team_id)->toBe($this->user->personalTeam()->id);
});
