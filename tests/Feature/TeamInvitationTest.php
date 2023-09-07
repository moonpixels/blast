<?php

use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Notifications\TeamInvitationNotification;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = login();
    $this->ownedTeam = getTeamForUser($this->user, 'Owned Team');
    $this->memberTeam = getTeamForUser($this->user, 'Member Team');
});

test('users can create invitations for teams they own', function () {
    Notification::fake();

    $this->post(route('teams.invitations.store', [$this->ownedTeam]), [
        'email' => 'user@example.com',
    ])->assertSessionHas('success', [
        'title' => 'Invitation sent',
        'message' => 'An invitation has been sent to user@example.com.',
    ]);

    $invitation = TeamInvitation::first();

    expect($invitation->team->is($this->ownedTeam))->toBeTrue()
        ->and($invitation->email)->toBe('user@example.com');

    Notification::assertSentTo($invitation, TeamInvitationNotification::class);
});

test('users cannot create invitations for teams they do not own', function () {
    Notification::fake();

    $this->post(route('teams.invitations.store', [$this->memberTeam]), [
        'email' => 'user@example.com',
    ])->assertForbidden();

    expect(TeamInvitation::count())->toBe(0);

    Notification::assertNothingSent();
});

test('users can accept team invitations', function () {
    $invitation = createTeamInvitation(attributes: ['email' => $this->user->email]);

    $this->get($invitation->accept_url)
        ->assertRedirectToRoute('links.index')
        ->assertSessionHas('success', [
            'title' => 'Invitation accepted',
            'message' => "You have been added to the {$invitation->team->name} team.",
        ]);

    $this->user->refresh();

    expect($invitation)->toBeDeleted()
        ->and($this->user->belongsToTeam($invitation->team))->toBeTrue()
        ->and($this->user->currentTeam->is($invitation->team))->toBeTrue();
});

test('users can resend invitations for teams the own', function () {
    Notification::fake();

    $invitation = createTeamInvitation(attributes: ['team_id' => $this->ownedTeam->id]);

    $this->get(route('teams.invitations.resend', [$invitation->team, $invitation]))
        ->assertRedirect()
        ->assertSessionHas('success', [
            'title' => 'Invitation resent',
            'message' => "The invitation for {$invitation->email} has been resent.",
        ]);

    Notification::assertSentTo($invitation, TeamInvitationNotification::class);
});

test('users cannot resend invitations for teams they do not own', function () {
    Notification::fake();

    $invitation = createTeamInvitation();

    $this->get(route('teams.invitations.resend', [$invitation->team, $invitation]))
        ->assertForbidden();

    Notification::assertNothingSent();
});

test('users can delete invitations for teams they own', function () {
    $invitation = createTeamInvitation(attributes: ['team_id' => $this->ownedTeam->id]);

    $this->delete(route('teams.invitations.destroy', [$invitation->team, $invitation]))
        ->assertRedirect()
        ->assertSessionHas('success', [
            'title' => 'Invitation cancelled',
            'message' => "The invitation for {$invitation->email} has been cancelled.",
        ]);

    expect($invitation)->toBeDeleted();
});

test('users can delete invitations for themselves', function () {
    $invitation = createTeamInvitation(attributes: ['email' => $this->user->email]);

    $this->delete(route('teams.invitations.destroy', [$invitation->team, $invitation]))
        ->assertRedirect()
        ->assertSessionHas('success', [
            'title' => 'Invitation cancelled',
            'message' => "The invitation for {$invitation->email} has been cancelled.",
        ]);

    expect($invitation)->toBeDeleted();
});

test('users cannot delete invitations for teams they do not own', function () {
    $invitation = createTeamInvitation();

    $this->delete(route('teams.invitations.destroy', [$invitation->team, $invitation]))
        ->assertForbidden();

    expect($invitation)->toExistInDatabase();
});

test('users can filter team invitations', function () {
    createTeamInvitation(attributes: [
        'team_id' => $this->ownedTeam->id,
        'email' => 'test@example.com',
    ]);

    createTeamInvitation(
        attributes: ['team_id' => $this->ownedTeam->id],
        states: ['count' => 5],
    );

    $this->get(route('teams.show', [
        'team' => $this->ownedTeam,
        'view' => 'invitations',
        'query' => 'test@example.com',
    ]))->assertInertia(fn (Assert $page) => $page
        ->component('teams/show')
        ->where('filters.view', 'invitations')
        ->where('filters.query', 'test@example.com')
        ->has('invitations.data', 1)
        ->has('invitations.data.0', fn (Assert $page) => $page
            ->where('email', 'test@example.com')
            ->etc()
        )
    );
});
