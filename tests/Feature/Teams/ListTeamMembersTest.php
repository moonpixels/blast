<?php

use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->withTeamMembership()->create();

    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('allows owners to remove a team member from the team', function () {
    $this->actingAs($this->membershipTeam->owner)
        ->delete(route('teams.members.destroy', [$this->membershipTeam, $this->user]))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($this->user->belongsToTeam($this->membershipTeam))->toBeFalse();
});

it('allows the team member to remove themselves from the team', function () {
    $this->delete(route('teams.members.destroy', [$this->membershipTeam, $this->user]))
        ->assertRedirectToRoute('teams.show', $this->user->personalTeam())
        ->assertSessionHas('success');

    expect($this->user->belongsToTeam($this->membershipTeam))->toBeFalse();
});

it('does not allow other users to remove a team member from the team', function () {
    $this->actingAs(User::factory()->create());

    $this->delete(route('teams.members.destroy', [$this->membershipTeam, $this->user]))
        ->assertForbidden();

    expect($this->user->belongsToTeam($this->membershipTeam))->toBeTrue();
});
