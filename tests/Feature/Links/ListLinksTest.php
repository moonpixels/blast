<?php

use App\Models\Link;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()
        ->withStandardTeam()
        ->withTeamMembership()
        ->create();

    $this->standardTeam = $this->user->ownedTeams()->where('personal_team', false)->first();
    $this->membershipTeam = $this->user->teams->first();

    $this->actingAs($this->user);
});

it('shows the list links page to users', function () {
    $this->get(route('links.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->where('filters.query', null)
            ->missing('shortenedLink')
            ->has('links.data', 0)
        );
});

it('does not show the list links page to guests', function () {
    $this->post(route('logout'));

    $this->get(route('links.index'))
        ->assertRedirectToRoute('login');
});

it('gets the correct links for the users current team', function () {
    $standardTeamLinks = Link::factory(3)->for($this->standardTeam)->create();
    $membershipTeamLinks = Link::factory()->for($this->membershipTeam)->create();

    $this->user->switchTeam($this->standardTeam);

    $this->get(route('links.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->has('links.data', 3)
            ->has('links.data.0', fn (Assert $page) => $page
                ->where('id', $standardTeamLinks[0]->id)
                ->etc()
            )
            ->has('links.data.1', fn (Assert $page) => $page
                ->where('id', $standardTeamLinks[1]->id)
                ->etc()
            )
            ->has('links.data.2', fn (Assert $page) => $page
                ->where('id', $standardTeamLinks[2]->id)
                ->etc()
            )
        );

    $this->user->switchTeam($this->membershipTeam);

    $this->get(route('links.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->has('links.data', 1)
            ->has('links.data.0', fn (Assert $page) => $page
                ->where('id', $membershipTeamLinks->id)
                ->etc()
            )
        );
});

it('gets the shortened link from the session if it exists', function () {
    $this->get(route('links.index'), [
        'X-Inertia-Partial-Component' => 'Links/Index',
        'X-Inertia-Partial-Data' => ['shortenedLink'],
    ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->where('shortenedLink', null)
        );

    $link = Link::factory()->for($this->standardTeam)->create();
    session()->flash('shortened_link', $link);

    $this->get(route('links.index'), [
        'X-Inertia-Partial-Component' => 'Links/Index',
        'X-Inertia-Partial-Data' => ['shortenedLink'],
    ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->has('shortenedLink', fn (Assert $page) => $page
                ->where('id', $link->id)
                ->etc()
            )
        );
});

it('gets the correct links when searching', function () {
    Link::factory(5)->for($this->user->currentTeam)->create();
    $link = Link::factory()->for($this->user->currentTeam)->create(['alias' => 'myAlias']);

    $this->get(route('links.index', ['query' => 'myAlias']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Links/Index')
            ->where('filters.query', 'myAlias')
            ->has('links.data', 1)
            ->has('links.data.0', fn (Assert $page) => $page
                ->where('id', $link->id)
                ->etc()
            )
        );
});
