<?php

use App\Models\Team;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use App\Services\TeamMemberService;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->teamMemberService = app(TeamMemberService::class);
});

it('can invite a new team member to the given team', function () {
    Notification::fake();

    $invitation = $this->teamMemberService->inviteMemberToTeam($this->team, [
        'email' => 'user@blst.to',
    ]);

    expect($invitation->email)->toBe('user@blst.to');

    Notification::assertSentTo(
        $invitation,
        TeamInvitationNotification::class,
    );
});
