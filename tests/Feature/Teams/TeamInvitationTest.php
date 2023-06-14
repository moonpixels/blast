<?php

use App\Mail\TeamInvitationMail;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->existingTeamMember = User::factory()->create();
    $this->team->users()->attach($this->existingTeamMember);

    $this->actingAs($this->user);
});

it('allows owners to invite team members', function () {
    Notification::fake();

    $this->post(route('teams.invitations.store', $this->team), [
        'email' => 'user@blst.to',
    ])->assertRedirect()->assertSessionHas('success');

    $this->assertDatabaseHas('team_invitations', [
        'team_id' => $this->team->id,
        'email' => 'user@blst.to',
    ]);

    assertNotificationSentTo($this->team->invitations->first());
});

it('does not allow non-owners to invite team members', function () {
    Notification::fake();

    $this->actingAs($this->existingTeamMember);

    $this->post(route('teams.invitations.store', $this->team), [
        'email' => 'user@blst.to',
    ])->assertForbidden();

    $this->assertDatabaseEmpty('team_invitations');

    Notification::assertNothingSent();
});

it('does not invite a user that is already a member of the team', function () {
    Notification::fake();

    $this->post(route('teams.invitations.store', $this->team), [
        'email' => $this->user->email,
    ])->assertInvalid('email');

    $this->post(route('teams.invitations.store', $this->team), [
        'email' => $this->existingTeamMember->email,
    ])->assertInvalid('email');

    $this->assertDatabaseEmpty('team_invitations');

    Notification::assertNothingSent();
});

it('does not invite a user that has already been invited to the team', function () {
    Notification::fake();

    TeamInvitation::factory()->create([
        'team_id' => $this->team->id,
        'email' => 'user@blst.to',
    ]);

    $this->post(route('teams.invitations.store', $this->team), [
        'email' => 'user@blst.to',
    ])->assertInvalid('email');

    $this->assertDatabaseCount('team_invitations', 1);

    Notification::assertNothingSent();
});

it('allows the user to accept the invitation', function () {
    $teamInvitation = TeamInvitation::factory()->create([
        'team_id' => $this->team->id,
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

    $this->assertTrue($user->belongsToTeam($this->team));

    $this->assertEquals($this->team->id, $user->fresh()->current_team_id);
});

it('does not allow the user to accept the invitation if they are already on the team', function () {
    $teamInvitation = TeamInvitation::factory()->create([
        'team_id' => $this->team->id,
        'email' => $this->existingTeamMember->email,
    ]);

    $this->actingAs($this->existingTeamMember)
        ->get($teamInvitation->accept_url)
        ->assertRedirect(config('fortify.home'))
        ->assertSessionHas('error');

    $this->assertModelMissing($teamInvitation);

    $this->assertTrue($this->existingTeamMember->belongsToTeam($this->team));
});

it('allows owners to cancel invitations', function () {
    $teamInvitation = TeamInvitation::factory()->for($this->team)->create();

    $this->delete(route('invitations.destroy', [$teamInvitation]))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertModelMissing($teamInvitation);
});

it('does not allow non-owners to cancel invitations', function () {
    $teamInvitation = TeamInvitation::factory()->for($this->team)->create();

    $this->actingAs($this->existingTeamMember)
        ->delete(route('invitations.destroy', [$teamInvitation]))
        ->assertForbidden();

    $this->assertModelExists($teamInvitation);
});

it('allows owners to resend invitations', function () {
    Notification::fake();

    $teamInvitation = TeamInvitation::factory()->for($this->team)->create();

    $this->post(route('resent-invitations.store'), ['invitation_id' => $teamInvitation->id])
        ->assertRedirect()
        ->assertSessionHas('success');

    assertNotificationSentTo($this->team->invitations->first());
});

it('does not allow non-owners to resend invitations', function () {
    Notification::fake();

    $teamInvitation = TeamInvitation::factory()->for($this->team)->create();

    $this->actingAs($this->existingTeamMember)
        ->post(route('resent-invitations.store'), ['invitation_id' => $teamInvitation->id])
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
