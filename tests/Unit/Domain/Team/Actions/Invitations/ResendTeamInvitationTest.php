<?php

use Domain\Team\Actions\Invitations\ResendTeamInvitationAction;
use Domain\Team\Mail\TeamInvitationMail;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->invitation = createTeamInvitation();
});

it('resends a team invitation', function () {
    Mail::fake();

    expect(ResendTeamInvitationAction::run($this->invitation))->toBeTrue();

    Mail::assertSent(TeamInvitationMail::class);
});
