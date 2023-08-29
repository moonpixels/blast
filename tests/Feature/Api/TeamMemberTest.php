<?php

beforeEach(function () {
    $this->user = createUser();
    $this->team = getTeamForUser($this->user, 'Member Team');

    loginAs($this->team->owner);

    $this->jsonStructure = [
        'id',
        'name',
        'email',
        'initials',
    ];

    $this->jsonCollectionStructure = [
        'data' => [
            '*' => $this->jsonStructure,
        ],
    ];
});

test('users can retrieve team members', function () {
    $this->getJson(route('api.teams.members.show', [$this->team, $this->user]))
        ->assertOk()
        ->assertJsonStructure(['data' => $this->jsonStructure]);
});

test('users can delete team members', function () {
    $this->deleteJson(route('api.teams.members.destroy', [$this->team, $this->user]))
        ->assertNoContent();

    expect($this->user->belongsToTeam($this->team))->toBeFalse();
});

test('users can list team members', function () {
    $this->getJson(route('api.teams.members.index', $this->team))
        ->assertOk()
        ->assertJsonStructure($this->jsonCollectionStructure);
});
