<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->actingAs($this->user);
});

it('creates a team', function () {
    $this->post(route('teams.store'), [
        'name' => 'Test Team',
    ])->assertRedirectToRoute('teams.show', Team::where('name', 'Test Team')->first());

    expect($this->user->fresh()->ownedTeams->count())->toBe(3);

    $this->assertDatabaseHas('teams', [
        'owner_id' => $this->user->id,
        'name' => 'Test Team',
    ]);

    expect($this->user->current_team_id)->toBe(Team::where('name', 'Test Team')->first()->id);
});

it('does not create a team with a duplicate name for the same user', function () {
    $this->post(route('teams.store'), [
        'name' => $this->team->name,
    ])->assertSessionHasErrors('name');

    expect($this->user->fresh()->ownedTeams->count())->toBe(2);
});

it('creates a team with a duplicate name for a different user', function () {
    $team = Team::factory()->create();

    $this->post(route('teams.store'), [
        'name' => $team->name,
    ])->assertRedirectToRoute('teams.show', Team::where([
        'owner_id' => $this->user->id,
        'name' => $team->name,
    ])->first());

    expect($this->user->fresh()->ownedTeams->count())->toBe(3);

    $this->assertDatabaseHas('teams', [
        'owner_id' => $this->user->id,
        'name' => $team->name,
    ]);

    expect($this->user->current_team_id)->toBe(Team::where([
        'owner_id' => $this->user->id,
        'name' => $team->name,
    ])->first()->id);
});
