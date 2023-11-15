<?php

use Domain\Team\Mail\TeamInvitationMail;

it('has mail content', function () {
    $invitation = createTeamInvitation();
    $mailable = new TeamInvitationMail($invitation);

    $mailable->assertHasSubject("Invitation to join the {$invitation->team->name} team");
    $mailable->assertSeeInHtml('Team Invitation');
    $mailable->assertSeeInHtml('Accept invitation');
    $mailable->assertSeeInHtml($invitation->accept_url);
});
