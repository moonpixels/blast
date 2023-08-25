<?php

use App\Domain\Team\Models\Team;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = createUser();
    Sanctum::actingAs($this->user);
});

test('users can create teams', function () {
    $response = $this->postJson(route('api.teams.store'), [
        'name' => 'Test Team',
    ])->assertJson([
        'data' => [
            'name' => 'Test Team',
        ],
    ])->assertCreated();

    $team = Team::find($response->json('data.id'));

    expect($team)->toExistInDatabase()
        ->and($team->owner->is($this->user))->toBeTrue()
        ->and($team->name)->toBe('Test Team');
});

test('users can retrieve teams', function () {
    $team = createTeam(attributes: ['owner_id' => $this->user->id]);

    $this->getJson(route('api.teams.show', $team))
        ->assertJson([
            'data' => [
                'name' => $team->name,
            ],
        ])->assertOk();
});

test('users can update teams', function () {
    $team = createTeam(attributes: ['owner_id' => $this->user->id]);

    $this->putJson(route('api.teams.update', $team), [
        'name' => 'Updated Team',
    ])->assertJson([
        'data' => [
            'name' => 'Updated Team',
        ],
    ])->assertOk();

    $team->refresh();

    expect($team->name)->toBe('Updated Team');
});

test('users can delete teams', function () {
    $team = createTeam(attributes: ['owner_id' => $this->user->id]);

    $this->deleteJson(route('api.teams.destroy', $team))
        ->assertNoContent();

    expect($team)->toBeSoftDeleted();
});
