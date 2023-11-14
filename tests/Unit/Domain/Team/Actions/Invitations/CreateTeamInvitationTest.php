<?php

use Domain\Team\Actions\Invitations\CreateTeamInvitationAction;
use Domain\Team\DTOs\TeamInvitationData;
use Domain\Team\Mail\TeamInvitationMail;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->team = createTeam();
});

it('creates a team invitation', function () {
    Mail::fake();

    $invitation = CreateTeamInvitationAction::run($this->team, TeamInvitationData::from([
        'email' => 'test@example.com',
    ]));

    expect($invitation)->toExistInDatabase()
        ->and($invitation->email)->toBe('test@example.com')
        ->and($invitation->team->is($this->team))->toBeTrue();

    Mail::assertSent(TeamInvitationMail::class);
});
