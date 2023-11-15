<?php

use Domain\Team\Mail\TeamInvitationMail;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->team = createTeam();

    loginAs($this->team->owner);

    $this->jsonStructure = [
        'id',
        'team',
        'email',
    ];

    $this->jsonCollectionStructure = [
        'data' => [
            '*' => $this->jsonStructure,
        ],
    ];
});

test('users can create team invitations', function () {
    Mail::fake();

    $response = $this->postJson(route('api.teams.invitations.store', $this->team), [
        'email' => 'test@example.com',
    ])->assertCreated()->assertJsonStructure(['data' => $this->jsonStructure]);

    $invitation = TeamInvitation::find($response->json('data.id'));

    expect($invitation)->toExistInDatabase()
        ->and($invitation->team->is($this->team))->toBeTrue()
        ->and($invitation->email)->toBe('test@example.com');

    Mail::assertSent(TeamInvitationMail::class);
});

test('users can retrieve team invitations', function () {
    $invitation = createTeamInvitation(attributes: ['team_id' => $this->team->id]);

    $this->getJson(route('api.teams.invitations.show', [$this->team, $invitation]))
        ->assertOk()
        ->assertJsonStructure(['data' => $this->jsonStructure]);
});

test('users can delete team invitations', function () {
    $invitation = createTeamInvitation(attributes: ['team_id' => $this->team->id]);

    $this->deleteJson(route('api.teams.invitations.destroy', [$this->team, $invitation]))
        ->assertNoContent();

    expect($invitation)->toBeDeleted();
});

test('users can list team invitations', function () {
    createTeamInvitation(attributes: ['team_id' => $this->team->id]);

    $this->getJson(route('api.teams.invitations.index', $this->team))
        ->assertOk()
        ->assertJsonStructure($this->jsonCollectionStructure)
        ->assertJsonCount(1, 'data');
});
