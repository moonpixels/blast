<?php

use App\Domain\Team\Models\Team;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = login();
});

test('users can create teams', function () {
    $this->post(route('teams.store'), [
        'name' => 'Test Team',
    ])->assertRedirectToRoute('teams.show', Team::whereName('Test Team')->first());

    $team = Team::whereName('Test Team')->first();

    expect($this->user->ownsTeam($team))->toBeTrue()
        ->and($this->user->currentTeam->is($team))->toBeTrue();
});

test('users cannot choose duplicate team names', function () {
    $this->post(route('teams.store'), [
        'name' => 'Owned Team',
    ])->assertInvalid(['name']);
});

test('users can view teams', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    $this->get(route('teams.show', $team))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('team', fn (Assert $page) => $page
                ->where('id', $team->id)
                ->where('name', $team->name)
                ->etc())
            ->has('filters.view')
            ->has('filters.query')
            ->has('members')
            ->missing('invitations')
        );
});

test('users can update teams they own', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    $this->put(route('teams.update', $team), [
        'name' => 'Updated Team Name',
    ])->assertRedirect()->assertSessionHas('success', [
        'title' => 'Team updated',
        'message' => 'Updated Team Name team has been updated successfully.',
    ]);

    $team->refresh();

    expect($team->name)->toBe('Updated Team Name');
});

test('users cannot update teams they do not own', function () {
    $team = getTeamForUser($this->user, 'Member Team');

    $this->put(route('teams.update', $team), [
        'name' => 'Updated Team Name',
    ])->assertForbidden();

    $team->refresh();

    expect($team->name)->not->toBe('Updated Team Name');
});

test('users can delete teams they own', function () {
    $team = getTeamForUser($this->user, 'Owned Team');
    $this->user->switchTeam($team);

    $this->delete(route('teams.destroy', $team))
        ->assertRedirectToRoute('links.index')
        ->assertSessionHas('success', [
            'title' => 'Team deleted',
            'message' => 'Owned Team team has been deleted successfully.',
        ]);

    $this->user->refresh();

    expect($team)->toBeSoftDeleted()
        ->and($this->user->currentTeam->is($this->user->personalTeam()))->toBeTrue();
});

test('users cannot delete their personal team', function () {
    $this->delete(route('teams.destroy', $this->user->personalTeam()))
        ->assertForbidden();

    expect($this->user->personalTeam())->toExistInDatabase();
});
