<?php

use App\Domain\Team\Actions\Invitations\ResendTeamInvitation;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->invitation = createTeamInvitation();
});

it('resends a team invitation', function () {
    Notification::fake();

    expect(ResendTeamInvitation::run($this->invitation))->toBeTrue();

    Notification::assertSentTo(
        $this->invitation,
        function (TeamInvitationNotification $notification) {
            return expect($notification->invitation->is($this->invitation))->toBeTrue();
        }
    );
});
