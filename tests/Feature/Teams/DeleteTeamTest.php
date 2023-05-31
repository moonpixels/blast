<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->actingAs($this->user);
});

it('deletes the team', function () {
    $this->delete(route('teams.destroy', $this->team))
        ->assertRedirectToRoute('links.index')
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('teams', [
        'id' => $this->team->id,
    ]);
});

it('only allows owners to delete the team', function () {
    $team = Team::factory()->create();

    $this->delete(route('teams.destroy', $team))
        ->assertForbidden();

    $team->users()->attach($this->user);

    $this->delete(route('teams.destroy', $team))
        ->assertForbidden();
});

it('does not delete personal teams', function () {
    $this->team->update(['personal_team' => true]);

    $this->delete(route('teams.destroy', $this->team))
        ->assertForbidden();
});
