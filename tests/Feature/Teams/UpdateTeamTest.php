<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->actingAs($this->user);
});

it('updated the team', function () {
    $this->put(route('teams.update', $this->team), [
        'name' => 'Test Team',
    ])->assertRedirect()->assertSessionHas('success');

    $this->assertDatabaseHas('teams', [
        'id' => $this->team->id,
        'name' => 'Test Team',
    ]);
});

it('only allows owners to update the team', function () {
    $team = Team::factory()->create();

    $this->put(route('teams.update', $team), [
        'name' => 'Test Team',
    ])->assertForbidden();

    $team->users()->attach($this->user);

    $this->put(route('teams.update', $team), [
        'name' => 'Test Team',
    ])->assertForbidden();
});
