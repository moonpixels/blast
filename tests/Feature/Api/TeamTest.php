<?php

use App\Domain\Team\Models\Team;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = createUser();
    Sanctum::actingAs($this->user);
    $this->team = getTeamForUser($this->user, 'Owned Team');

    $this->jsonStructure = [
        'id',
        'name',
        'personal_team',
    ];

    $this->jsonCollectionStructure = [
        'data' => [
            '*' => $this->jsonStructure,
        ],
    ];
});

test('users can create teams', function () {
    $response = $this->postJson(route('api.teams.store'), [
        'name' => 'Test Team',
    ])->assertCreated()->assertJsonStructure(['data' => $this->jsonStructure]);

    $team = Team::find($response->json('data.id'));

    expect($team)->toExistInDatabase()
        ->and($team->owner->is($this->user))->toBeTrue()
        ->and($team->name)->toBe('Test Team');
});

test('users can retrieve teams', function () {
    $this->getJson(route('api.teams.show', $this->team))
        ->assertOk()
        ->assertJsonStructure(['data' => $this->jsonStructure]);
});

test('users can update teams', function () {
    $this->putJson(route('api.teams.update', $this->team), [
        'name' => 'Updated Team',
    ])->assertOk()->assertJsonStructure(['data' => $this->jsonStructure]);

    $this->team->refresh();

    expect($this->team->name)->toBe('Updated Team');
});

test('users can delete teams', function () {
    $this->deleteJson(route('api.teams.destroy', $this->team))
        ->assertNoContent();

    expect($this->team)->toBeSoftDeleted();
});

test('users can list teams', function () {
    $this->getJson(route('api.teams.index'))
        ->assertOk()
        ->assertJsonStructure($this->jsonCollectionStructure)
        ->assertJsonCount($this->user->allTeams()->count(), 'data');
});
