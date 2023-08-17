<?php

use App\Domain\Team\Actions\Invitations\CreateInvitationForTeam;
use App\Domain\Team\Data\TeamInvitationData;
use App\Domain\Team\Mail\TeamInvitationMail;
use App\Domain\Team\Models\User;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();
});

it('can invite a new team member to the given team', function () {
    Notification::fake();

    $invitation = CreateInvitationForTeam::run($this->team, TeamInvitationData::from([
        'email' => 'user@blst.to',
    ]));

    expect($invitation->email)->toBe('user@blst.to');

    Notification::assertSentTo($invitation, TeamInvitationNotification::class,
        function ($notification) use ($invitation) {
            $mail = $notification->toMail($invitation);

            return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
        });
});
