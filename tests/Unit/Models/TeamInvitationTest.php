<?php

use App\Models\TeamInvitation;

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

it('can filter invitations by email', function () {
    TeamInvitation::factory(5)->create();

    $invitations = TeamInvitation::whereEmailLike('@blst.to')->get();

    expect($invitations)->toHaveCount(1)
        ->and($invitations->first()->email)->toBe('john.doe@blst.to');
});
