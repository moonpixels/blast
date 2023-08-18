<?php

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Models\Visit;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->team = Team::factory()->create();
});

it('belongs to an owner', function () {
    expect($this->team->owner)->toBeInstanceOf(User::class);
});

it('has many members', function () {
    $this->team->members()->attach(User::factory(5)->create());

    $members = $this->team->members;

    expect($members)->toHaveCount(5)
        ->and($members)->toBeInstanceOf(Collection::class)
        ->and($members)->each->toBeInstanceOf(User::class);
});

it('can get all members including the owner', function () {
    $this->team->members()->attach(User::factory(5)->create());

    $membersAndOwner = $this->team->membersAndOwner();

    expect($membersAndOwner)->toHaveCount(6)
        ->and($membersAndOwner)->toBeInstanceOf(Collection::class)
        ->and($membersAndOwner)->each->toBeInstanceOf(User::class);
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
