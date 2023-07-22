<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withTeamMembership()->create();
    $this->team = $this->user->teams->first();
    $this->teamMembership = $this->user->teamMemberships()->first();

    $this->actingAs($this->user);
});

it('allows owners to remove a team member from the team', function () {
    $this->actingAs($this->team->owner)
        ->delete(route('team-memberships.destroy', [$this->teamMembership]))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertModelMissing($this->teamMembership);
});

it('allows the team member to remove themselves from the team', function () {
    $this->delete(route('team-memberships.destroy', [$this->teamMembership]))
        ->assertRedirectToRoute('teams.show', $this->user->personalTeam())
        ->assertSessionHas('success');

    $this->assertModelMissing($this->teamMembership);
});

it('does not allow other users to remove a team member from the team', function () {
    $this->actingAs(User::factory()->create());

    $this->delete(route('team-memberships.destroy', [$this->teamMembership]))
        ->assertForbidden();

    $this->assertModelExists($this->teamMembership);
});
