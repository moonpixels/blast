<?php

use App\Models\Team;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->actingAs($this->user);
});

it('shows the teams page', function () {
    $this->get(route('teams.show', $this->team))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Teams/Show')
            ->has('team', fn (Assert $page) => $page
                ->where('id', $this->team->id)
                ->where('name', $this->team->name)
                ->where('personal_team', $this->team->personal_team)
                ->etc()
            )
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
