<?php

use App\Exceptions\InvalidTeamMembershipException;
use App\Mail\TeamInvitationMail;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use App\Services\TeamInvitationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->create();

    $this->team = $this->user->ownedTeams()->where('personal_team', false)->first();

    $this->invitedUser = User::factory()->create();

    $this->teamInvitation = TeamInvitation::factory()->for($this->team)->create([
        'email' => $this->invitedUser->email,
    ]);

    $this->teamInvitationService = app(TeamInvitationService::class);
});

it('can accept a team invitation', function () {
    $this->assertTrue($this->teamInvitationService->acceptInvitation($this->teamInvitation));

    $this->assertTrue($this->invitedUser->belongsToTeam($this->team));

    $this->assertEquals($this->team->id, $this->invitedUser->fresh()->current_team_id);

    $this->assertModelMissing($this->teamInvitation);
});

it('throws an exception when accepting an invitation if the user is already on the team', function () {
    $this->invitedUser->teams()->attach($this->team);

    try {
        $this->teamInvitationService->acceptInvitation($this->teamInvitation);
    } catch (InvalidTeamMembershipException $e) {
        $this->assertModelMissing($this->teamInvitation);
        throw $e;
    }
})->throws(InvalidTeamMembershipException::class);

it('throws an exception when accepting an invitation if the user does not exist', function () {
    $this->teamInvitation->update(['email' => 'invalid-email@blst.to']);

    $this->teamInvitationService->acceptInvitation($this->teamInvitation);
})->throws(ModelNotFoundException::class);

it('can cancel a team invitation', function () {
    $this->assertTrue($this->teamInvitationService->cancelInvitation($this->teamInvitation));

    $this->assertModelMissing($this->teamInvitation);
});

it('can resend a team invitation', function () {
    Notification::fake();

    $this->assertTrue($this->teamInvitationService->resendInvitation($this->teamInvitation));

    Notification::assertSentTo($this->teamInvitation, TeamInvitationNotification::class, function ($notification) {
        $mail = $notification->toMail($this->teamInvitation);

        return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
    });
});

it('can invite a new team member to the given team', function () {
    Notification::fake();

    $invitation = $this->teamInvitationService->createInvitationForTeam($this->team, [
        'email' => 'user@blst.to',
    ]);

    expect($invitation->email)->toBe('user@blst.to');

    Notification::assertSentTo($invitation, TeamInvitationNotification::class, function ($notification) {
        $mail = $notification->toMail($this->teamInvitation);

        return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
    });
});
