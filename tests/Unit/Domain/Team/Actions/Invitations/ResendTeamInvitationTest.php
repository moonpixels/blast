<?php

use App\Domain\Team\Actions\Invitations\ResendTeamInvitation;
use App\Domain\Team\Mail\TeamInvitationMail;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Models\User;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

    $this->invitedUser = User::factory()->create();

    $this->teamInvitation = TeamInvitation::factory()->for($this->team)->create([
        'email' => $this->invitedUser->email,
    ]);
});

it('can resend a team invitation', function () {
    Notification::fake();

    expect(ResendTeamInvitation::run($this->teamInvitation))->toBeTrue();

    Notification::assertSentTo($this->teamInvitation, TeamInvitationNotification::class, function ($notification) {
        $mail = $notification->toMail($this->teamInvitation);

        return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
    });
});
