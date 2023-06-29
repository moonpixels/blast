<?php

use App\Actions\Teams\ResendTeamInvitation;
use App\Mail\TeamInvitationMail;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
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

    expect(ResendTeamInvitation::execute($this->teamInvitation))->toBeTrue();

    Notification::assertSentTo($this->teamInvitation, TeamInvitationNotification::class, function ($notification) {
        $mail = $notification->toMail($this->teamInvitation);

        return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
    });
});
