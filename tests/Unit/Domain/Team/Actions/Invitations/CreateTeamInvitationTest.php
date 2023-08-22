<?php

use App\Domain\Team\Actions\Invitations\CreateTeamInvitation;
use App\Domain\Team\Data\TeamInvitationData;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->team = createTeam();
});

it('creates a team invitation', function () {
    Notification::fake();

    $invitation = CreateTeamInvitation::run($this->team, TeamInvitationData::from([
        'email' => 'test@example.com',
    ]));

    expect($invitation)->toExistInDatabase()
        ->and($invitation->email)->toBe('test@example.com')
        ->and($invitation->team->is($this->team))->toBeTrue();

    Notification::assertSentTo(
        $invitation,
        function (TeamInvitationNotification $notification) use ($invitation) {
            return expect($notification->invitation->is($invitation))->toBeTrue();
        }
    );
});
