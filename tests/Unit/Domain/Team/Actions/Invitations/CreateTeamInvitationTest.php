<?php

use App\Domain\Team\Actions\Invitations\CreateTeamInvitation;
use App\Domain\Team\Data\TeamInvitationData;
use App\Domain\Team\Mail\TeamInvitationMail;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->notPersonal()->first();
});

it('can invite a new team member to the given team', function () {
    Notification::fake();

    $invitation = CreateTeamInvitation::run($this->team, TeamInvitationData::from([
        'email' => 'user@blst.to',
    ]));

    expect($invitation->email)->toBe('user@blst.to');

    Notification::assertSentTo($invitation, TeamInvitationNotification::class,
        function ($notification) use ($invitation) {
            $mail = $notification->toMail($invitation);

            return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
        });
});
