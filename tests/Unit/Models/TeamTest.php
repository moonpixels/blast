<?php

use App\Models\Link;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamMembership;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->team = Team::factory()->create();
});

it('belongs to an owner', function () {
    expect($this->team->owner)->toBeInstanceOf(User::class);
});

it('has many memberships', function () {
    TeamMembership::factory(5)->for($this->team)->create();

    $memberships = $this->team->memberships;

    expect($memberships)->toHaveCount(5)
        ->and($memberships)->toBeInstanceOf(Collection::class)
        ->and($memberships)->each->toBeInstanceOf(TeamMembership::class);
});

it('belongs to many users', function () {
    TeamMembership::factory(5)->for($this->team)->create();

    $users = $this->team->users;

    expect($users)->toHaveCount(5)
        ->and($users)->toBeInstanceOf(Collection::class)
        ->and($users)->each->toBeInstanceOf(User::class);
});

it('can get all users including the owner', function () {
    TeamMembership::factory(5)->for($this->team)->create();

    $allUsers = $this->team->allUsers();

    expect($allUsers)->toHaveCount(6)
        ->and($allUsers)->toBeInstanceOf(Collection::class)
        ->and($allUsers)->each->toBeInstanceOf(User::class);
});

it('has many invitations', function () {
    TeamInvitation::factory(5)->for($this->team)->create();

    $invitations = $this->team->invitations;

    expect($invitations)->toHaveCount(5)
        ->and($invitations)->toBeInstanceOf(Collection::class)
        ->and($invitations)->each->toBeInstanceOf(TeamInvitation::class);
});

it('has many links', function () {
    Link::factory(5)->for($this->team)->create();

    $links = $this->team->links;

    expect($links)->toHaveCount(5)
        ->and($links)->toBeInstanceOf(Collection::class)
        ->and($links)->each->toBeInstanceOf(Link::class);
});

it('has many link visits', function () {
    Link::factory(5)
        ->for($this->team)
        ->has(Visit::factory(3))
        ->create();

    $visits = $this->team->linkVisits;

    expect($visits)->toHaveCount(15)
        ->and($visits)->toBeInstanceOf(Collection::class)
        ->and($visits)->each->toBeInstanceOf(Visit::class);
});
