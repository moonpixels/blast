<?php

use App\Domain\Link\Models\Link;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = createUser();
    Sanctum::actingAs($this->user);
    $this->team = getTeamForUser($this->user, 'Owned Team');

    $this->jsonStructure = [
        'id',
        'short_url',
        'destination_url',
        'alias',
        'has_password',
        'visit_limit',
        'expires_at',
    ];

    $this->jsonCollectionStructure = [
        'data' => [
            '*' => $this->jsonStructure,
        ],
    ];
});

test('users can create links', function () {
    $response = $this->postJson(route('api.links.store'), [
        'team_id' => $this->team->id,
        'destination_url' => 'https://example.com',
        'alias' => 'testing',
        'password' => 'password',
        'visit_limit' => 10,
        'expires_at' => now()->addDay()->toIso8601String(),
    ])->assertCreated()->assertJsonStructure(['data' => $this->jsonStructure]);

    $link = Link::find($response->json('data.id'));

    expect($link)->toExistInDatabase()
        ->and($link->team->is($this->team))->toBeTrue()
        ->and($link->destination_url)->toBe('https://example.com')
        ->and($link->alias)->toBe('testing')
        ->and($link->has_password)->toBeTrue()
        ->and(Hash::check('password', $link->password))->toBeTrue()
        ->and($link->visit_limit)->toBe(10)
        ->and($link->expires_at->toIso8601String())->toBe(now()->addDay()->toIso8601String());
});

test('users can retrieve links', function () {
    $link = createLink(attributes: ['team_id' => $this->team->id]);

    $this->getJson(route('api.links.show', $link))
        ->assertOk()
        ->assertJsonStructure(['data' => $this->jsonStructure]);
});

test('users can update links', function () {
    $link = createLink(attributes: ['team_id' => $this->team->id]);

    $team = getTeamForUser($this->user, 'Member Team');

    $this->putJson(route('api.links.update', $link), [
        'team_id' => $team->id,
        'destination_url' => 'https://example.com/updated',
        'alias' => 'updated',
        'password' => null,
        'visit_limit' => null,
        'expires_at' => null,
    ])->assertOk()->assertJsonStructure(['data' => $this->jsonStructure]);

    $link->refresh();

    expect($link->team->is($team))->toBeTrue()
        ->and($link->destination_url)->toBe('https://example.com/updated')
        ->and($link->alias)->toBe('updated')
        ->and($link->has_password)->toBeFalse()
        ->and($link->password)->toBeNull()
        ->and($link->visit_limit)->toBeNull()
        ->and($link->expires_at)->toBeNull();
});

test('users can delete links', function () {
    $link = createLink(attributes: ['team_id' => $this->team->id]);

    $this->deleteJson(route('api.links.destroy', $link))
        ->assertNoContent();

    expect($link)->toBeSoftDeleted();
});

test('users can list links', function () {
    createLink(
        attributes: ['team_id' => $this->team->id],
        states: ['count' => 5]
    );

    $this->getJson(route('api.links.index'))
        ->assertOk()
        ->assertJsonStructure($this->jsonCollectionStructure)
        ->assertJsonCount(5, 'data');
});

test('users can list links by team', function () {
    createLink(
        attributes: ['team_id' => $this->team->id],
        states: ['count' => 5]
    );

    $memberTeam = getTeamForUser($this->user, 'Member Team');

    createLink(
        attributes: ['team_id' => $memberTeam->id],
        states: ['count' => 10]
    );

    $this->getJson(route('api.links.index', [
        'team_id' => $this->team->id,
    ]))->assertOk()
        ->assertJsonStructure($this->jsonCollectionStructure)
        ->assertJsonCount(5, 'data');

    $this->getJson(route('api.links.index', [
        'team_id' => $memberTeam->id,
    ]))->assertOk()
        ->assertJsonStructure($this->jsonCollectionStructure)
        ->assertJsonCount(10, 'data');
});

test('users cannot list links for teams they are not a member of', function () {
    $team = createTeam();

    createLink(
        attributes: ['team_id' => $team->id],
        states: ['count' => 5]
    );

    $this->getJson(route('api.links.index', [
        'team_id' => $team->id,
    ]))->assertForbidden();
});
