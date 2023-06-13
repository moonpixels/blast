<?php

use App\Models\TeamInvitation;

beforeEach(function () {
    $this->teamInvitation = TeamInvitation::factory()->create();
});

it('generates a URL to accept the invitation', function () {
    expect($this->teamInvitation->accept_url)->toBeString()
        ->and($this->teamInvitation->accept_url)->toContain('accepted-invitations')
        ->and($this->teamInvitation->accept_url)->toContain($this->teamInvitation->id)
        ->and($this->teamInvitation->accept_url)->toContain('signature');
});
