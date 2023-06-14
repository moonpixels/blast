<?php

use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->team->users()->attach(User::factory()->create());
    $this->teamMember = $this->team->users()->first();

    $this->actingAs($this->user);
});

it('allows owners to remove a team member from the team', function () {
    $this->delete(route('teams.members.destroy', [$this->team, $this->teamMember->team_membership]))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertModelMissing($this->teamMember->team_membership);
});

it('allows the team member to remove themselves from the team', function () {
    $this->actingAs($this->teamMember);

    $this->delete(route('teams.members.destroy', [$this->team, $this->teamMember->team_membership]))
        ->assertRedirectToRoute('teams.show', $this->teamMember->personalTeam())
        ->assertSessionHas('success');

    $this->assertModelMissing($this->teamMember->team_membership);
});

it('does not allow other users to remove a team member from the team', function () {
    $this->actingAs(User::factory()->create());

    $this->delete(route('teams.members.destroy', [$this->team, $this->teamMember->team_membership]))
        ->assertForbidden();

    $this->assertModelExists($this->teamMember->team_membership);
});
