<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withTeamMembership()->create();
    $this->team = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('allows owners to remove a team member from the team', function () {
    $this->actingAs($this->team->owner)
        ->delete(route('teams.members.destroy', [$this->team, $this->team->team_membership]))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertModelMissing($this->team->team_membership);
});

it('allows the team member to remove themselves from the team', function () {
    $this->delete(route('teams.members.destroy', [$this->team, $this->team->team_membership]))
        ->assertRedirectToRoute('teams.show', $this->user->personalTeam())
        ->assertSessionHas('success');

    $this->assertModelMissing($this->team->team_membership);
});

it('does not allow other users to remove a team member from the team', function () {
    $this->actingAs(User::factory()->create());

    $this->delete(route('teams.members.destroy', [$this->team, $this->team->team_membership]))
        ->assertForbidden();

    $this->assertModelExists($this->team->team_membership);
});
