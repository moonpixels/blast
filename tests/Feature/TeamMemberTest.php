<?php

use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = login(['name' => 'Test User']);
    $this->ownedTeam = getTeamForUser($this->user, 'Owned Team');
    $this->memberTeam = getTeamForUser($this->user, 'Member Team');
    $this->user->switchTeam($this->memberTeam);
});

test('users can remove members from teams they own', function () {
    loginAs($this->memberTeam->owner);

    $this->delete(route('teams.members.destroy', [$this->memberTeam, $this->user]))
        ->assertRedirect()
        ->assertSessionHas('success', [
            'title' => 'Member removed',
            'message' => 'The member has been removed from the team.',
        ]);

    expect($this->user->belongsToTeam($this->memberTeam))->toBeFalse();
});

test('users can remove themselves from a team', function () {
    $this->delete(route('teams.members.destroy', [$this->memberTeam, $this->user]))
        ->assertRedirect()
        ->assertSessionHas('success', [
            'title' => 'You have left the team',
            'message' => 'You have left the Member Team team.',
        ]);

    expect($this->user->belongsToTeam($this->memberTeam))->toBeFalse();
});

test('users can filter team members', function () {
    loginAs($this->memberTeam->owner);

    $this->memberTeam->members()->attach(createUser(states: ['count' => 5]));

    $this->get(route('teams.show', [
        'team' => $this->memberTeam,
        'view' => 'members',
        'filter[search]' => 'Test User',
    ]))->assertInertia(fn (Assert $page) => $page
        ->component('teams/show')
        ->where('filters.view', 'members')
        ->where('filters.search', 'Test User')
        ->has('members.data', 1)
        ->has('members.data.0', fn (Assert $page) => $page
            ->where('id', $this->user->id)
            ->etc()
        )
    );
});
