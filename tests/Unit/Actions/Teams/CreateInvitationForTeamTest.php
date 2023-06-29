<?php

use App\Actions\Teams\CreateInvitationForTeam;
use App\Mail\TeamInvitationMail;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();
});

it('can invite a new team member to the given team', function () {
    Notification::fake();

    $invitation = CreateInvitationForTeam::execute($this->team, [
        'email' => 'user@blst.to',
    ]);

    expect($invitation->email)->toBe('user@blst.to');

    Notification::assertSentTo($invitation, TeamInvitationNotification::class,
        function ($notification) use ($invitation) {
            $mail = $notification->toMail($invitation);

            return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
        });
});
