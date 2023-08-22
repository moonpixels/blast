<?php

use App\Domain\Team\Models\Team;

beforeEach(function () {
    $this->invitation = createTeamInvitation();
});

it('belongs to a team', function () {
    expect($this->invitation->team)->toBeInstanceOf(Team::class);
});

it('has a searchable array', function () {
    expect($this->invitation->toSearchableArray())->toHaveKeys([
        'id',
        'team_id',
        'email',
        'created_at',
        'updated_at',
    ]);
});

it('generates an accept URL', function () {
    expect($this->invitation->accept_url)->toBe(
        URL::signedRoute('teams.invitations.accept', [
            'team' => $this->invitation->team,
            'invitation' => $this->invitation,
        ])
    );
});
