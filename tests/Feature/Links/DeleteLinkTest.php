<?php

use App\Domain\Link\Models\Link;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()
        ->withStandardTeam()
        ->withTeamMembership()
        ->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->personalTeamLink = Link::factory()->for($this->user->personalTeam())->create();
    $this->standardTeamLink = Link::factory()->for($this->standardTeam)->create();
    $this->membershipTeamLink = Link::factory()->for($this->membershipTeam)->create();

    $this->actingAs($this->user);
});

it('can delete a link for a users personal team', function () {
    $this->delete(route('links.destroy', $this->personalTeamLink))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertSoftDeleted('links', [
        'id' => $this->personalTeamLink->id,
    ]);
});

it('can delete a link for a team the user owns', function () {
    $this->delete(route('links.destroy', $this->standardTeamLink))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertSoftDeleted('links', [
        'id' => $this->standardTeamLink->id,
    ]);
});

it('can delete a link for a team the user is a member of', function () {
    $this->delete(route('links.destroy', $this->membershipTeamLink))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertSoftDeleted('links', [
        'id' => $this->membershipTeamLink->id,
    ]);
});

it('does not delete a link for a team the user is not a member of', function () {
    $this->actingAs(User::factory()->create());

    $this->delete(route('links.destroy', $this->standardTeamLink))
        ->assertForbidden();

    $this->assertDatabaseHas('links', [
        'id' => $this->standardTeamLink->id,
    ]);
});
