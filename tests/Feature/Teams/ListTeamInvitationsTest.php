<?php

use App\Domain\Team\Mail\TeamInvitationMail;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('allows owners to invite team members', function () {
    Notification::fake();

    $this->post(route('teams.invitations.store', $this->standardTeam), [
        'email' => 'user@blst.to',
    ])->assertRedirect()->assertSessionHas('success');

    $this->assertDatabaseHas('team_invitations', [
        'team_id' => $this->standardTeam->id,
        'email' => 'user@blst.to',
    ]);

    assertNotificationSentTo($this->standardTeam->invitations->first());
});

it('does not allow non-owners to invite team members', function () {
    Notification::fake();

    $this->post(route('teams.invitations.store', $this->membershipTeam), [
        'email' => 'user@blst.to',
    ])->assertForbidden();

    $this->assertDatabaseEmpty('team_invitations');

    Notification::assertNothingSent();
});

it('does not invite a user that is already a member of the team', function () {
    Notification::fake();

    $this->actingAs($this->membershipTeam->owner);

    $this->post(route('teams.invitations.store', $this->membershipTeam), [
        'email' => $this->membershipTeam->owner->email,
    ])->assertInvalid('email');

    $this->post(route('teams.invitations.store', $this->membershipTeam), [
        'email' => $this->user->email,
    ])->assertInvalid('email');

    $this->assertDatabaseEmpty('team_invitations');

    Notification::assertNothingSent();
});

it('does not invite a user that has already been invited to the team', function () {
    Notification::fake();

    TeamInvitation::factory()->create([
        'team_id' => $this->standardTeam->id,
        'email' => 'user@blst.to',
    ]);

    $this->post(route('teams.invitations.store', $this->standardTeam), [
        'email' => 'user@blst.to',
    ])->assertInvalid('email');

    $this->assertDatabaseCount('team_invitations', 1);

    Notification::assertNothingSent();
});

it('allows the user to accept the invitation', function () {
    $teamInvitation = TeamInvitation::factory()->create([
        'team_id' => $this->standardTeam->id,
        'email' => 'user@blst.to',
    ]);

    $user = User::factory()->create([
        'email' => 'user@blst.to',
    ]);

    $this->actingAs($user)
        ->get($teamInvitation->accept_url)
        ->assertRedirect(config('fortify.home'))
        ->assertSessionHas('success');

    $this->assertModelMissing($teamInvitation);

    $this->assertTrue($user->belongsToTeam($this->standardTeam));

    $this->assertEquals($this->standardTeam->id, $user->fresh()->current_team_id);
});

it('does not allow the user to accept the invitation if they are already on the team', function () {
    $teamInvitation = TeamInvitation::factory()->create([
        'team_id' => $this->membershipTeam->id,
        'email' => $this->user->email,
    ]);

    $this->get($teamInvitation->accept_url)
        ->assertRedirect(config('fortify.home'))
        ->assertSessionHas('error');

    $this->assertModelMissing($teamInvitation);

    $this->assertTrue($this->user->belongsToTeam($this->membershipTeam));
});

it('allows owners to cancel invitations', function () {
    $teamInvitation = TeamInvitation::factory()->for($this->standardTeam)->create();

    $this->delete(route('teams.invitations.destroy', [$this->standardTeam, $teamInvitation]))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertModelMissing($teamInvitation);
});

it('does not allow non-owners to cancel invitations', function () {
    $teamInvitation = TeamInvitation::factory()->for($this->membershipTeam)->create();

    $this->delete(route('teams.invitations.destroy', [$this->membershipTeam, $teamInvitation]))
        ->assertForbidden();

    $this->assertModelExists($teamInvitation);
});

it('allows owners to resend invitations', function () {
    Notification::fake();

    $teamInvitation = TeamInvitation::factory()->for($this->standardTeam)->create();

    $this->get(route('teams.invitations.resend', [$this->standardTeam, $teamInvitation]))
        ->assertRedirect()
        ->assertSessionHas('success');

    assertNotificationSentTo($this->standardTeam->invitations->first());
});

it('does not allow non-owners to resend invitations', function () {
    Notification::fake();

    $teamInvitation = TeamInvitation::factory()->for($this->membershipTeam)->create();

    $this->get(route('teams.invitations.resend', [$this->membershipTeam, $teamInvitation]))
        ->assertForbidden();

    Notification::assertNothingSent();
});

function assertNotificationSentTo(TeamInvitation $invitation): void
{
    Notification::assertSentTo(
        $invitation,
        TeamInvitationNotification::class,
        function ($notification) use ($invitation) {
            $mail = $notification->toMail($invitation);

            return expect($mail)->toBeInstanceOf(TeamInvitationMail::class);
        });
}
