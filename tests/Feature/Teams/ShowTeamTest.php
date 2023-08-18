<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\User\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->withStandardTeam()->withTeamMembership()->create();

    $this->standardTeam = $this->user->ownedTeams()->notPersonal()->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('shows the teams page to owners', function () {
    $this->get(route('teams.show', $this->standardTeam))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('team', fn (Assert $page) => $page
                ->where('id', $this->standardTeam->id)
                ->where('name', $this->standardTeam->name)
                ->etc())
            ->has('members')
            ->missing('invitations')
        );
});

it('shows the teams page to members', function () {
    $this->get(route('teams.show', $this->membershipTeam))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('team', fn (Assert $page) => $page
                ->where('id', $this->membershipTeam->id)
                ->where('name', $this->membershipTeam->name)
                ->etc())
            ->missing('members')
            ->missing('invitations')
        );
});

it('does not show the teams page to guests', function () {
    $this->post(route('logout'));

    $this->get(route('teams.show', $this->standardTeam))
        ->assertRedirectToRoute('login');
});

it('does not show the teams page to users who do not belong to the team', function () {
    $this->get(route('teams.show', Team::factory()->create()))
        ->assertForbidden();
});

it('allows owners to switch the view mode to invitations', function () {
    $this->get(route('teams.show', [$this->standardTeam, 'view' => 'invitations']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('invitations')
            ->missing('memberships')
        );
});

it('allows owners to filter team memberships', function () {
    $this->standardTeam->members()->attach(User::factory()->count(5)->create());

    $this->get(route('teams.show', [$this->standardTeam, 'query' => $this->standardTeam->members->first()->name]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('members.data', 1)
            ->where('members.data.0.name', $this->standardTeam->members->first()->name)
            ->missing('invitations')
        );
});

it('allows owners to filter invitations', function () {
    TeamInvitation::factory(5)->for($this->standardTeam)->create();

    $this->get(route('teams.show', [
        $this->standardTeam,
        'view' => 'invitations',
        'query' => $this->standardTeam->invitations->first()->email,
    ]))->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('invitations.data', 1)
            ->where('invitations.data.0.email', $this->standardTeam->invitations->first()->email)
            ->missing('members')
        );
});
