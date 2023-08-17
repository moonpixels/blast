<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Models\TeamMembership;
use App\Domain\Team\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()
        ->for($this->user, 'owner')
        ->has(TeamMembership::factory()->count(2), 'memberships')
        ->has(TeamInvitation::factory()->count(2), 'invitations')
        ->create();

    $this->actingAs($this->user);
});

it('shows the teams page to owners', function () {
    $this->get(route('teams.show', $this->team))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('team', fn (Assert $page) => $page
                ->where('id', $this->team->id)
                ->where('name', $this->team->name)
                ->where('personal_team', $this->team->personal_team)
                ->etc())
            ->has('memberships.data', 2)
            ->missing('invitations.data')
            ->missing('teamMembership')
        );
});

it('shows the teams page to members', function () {
    $user = $this->team->users->first();

    $this->actingAs($user)
        ->get(route('teams.show', $this->team))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('team', fn (Assert $page) => $page
                ->where('id', $this->team->id)
                ->where('name', $this->team->name)
                ->where('personal_team', $this->team->personal_team)
                ->etc())
            ->has('teamMembership', fn (Assert $page) => $page
                ->where('id', $user->teamMemberships()->first()->id)
                ->etc())
            ->missing('memberships')
            ->missing('invitations')
        );
});

it('does not show the teams page to guests', function () {
    $this->post(route('logout'));

    $this->get(route('teams.show', $this->team))
        ->assertRedirectToRoute('login');
});

it('does not show the teams page to users who do not belong to the team', function () {
    $this->get(route('teams.show', Team::factory()->create()))
        ->assertForbidden();
});

it('allows owners to switch the view mode to invitations', function () {
    $this->get(route('teams.show', [$this->team, 'view' => 'invitations']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('invitations.data', 2)
            ->missing('memberships.data')
        );
});

it('allows owners to filter team memberships', function () {
    $this->get(route('teams.show', [$this->team, 'query' => $this->team->users->first()->name]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('memberships.data', 1)
            ->where('memberships.data.0.user.name', $this->team->users->first()->name)
            ->missing('invitations.data')
        );
});

it('allows owners to filter invitations', function () {
    $this->get(route('teams.show', [
        $this->team,
        'view' => 'invitations',
        'query' => $this->team->invitations->first()->email,
    ]))->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('invitations.data', 1)
            ->where('invitations.data.0.email', $this->team->invitations->first()->email)
            ->missing('memberships.data')
        );
});
