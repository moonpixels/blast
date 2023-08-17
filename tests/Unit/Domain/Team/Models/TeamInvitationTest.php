<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;

beforeEach(function () {
    $this->teamInvitation = TeamInvitation::factory()->create([
        'email' => 'john.doe@blst.to',
    ]);
});

it('generates a URL to accept the invitation', function () {
    expect($this->teamInvitation->accept_url)->toBeString()
        ->and($this->teamInvitation->accept_url)->toContain('accepted-invitations')
        ->and($this->teamInvitation->accept_url)->toContain($this->teamInvitation->id)
        ->and($this->teamInvitation->accept_url)->toContain('signature');
});

it('returns the correct indexable array', function () {
    expect($this->teamInvitation->toSearchableArray())->toHaveKeys([
        'id',
        'team_id',
        'email',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to a team', function () {
    expect($this->teamInvitation->team)->toBeInstanceOf(Team::class);
});
