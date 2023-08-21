<?php

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Models\Visit;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->team = createTeam();
});

it('belongs to an owner', function () {
    expect($this->team->owner)->toBeInstanceOf(User::class);
});

it('belongs to many members', function () {
    $this->team->members()->attach(createUser(states: ['count' => 5]));

    expect($this->team->members)->toBeInstanceOf(Collection::class)
        ->and($this->team->members)->toHaveCount(5)
        ->and($this->team->members)->each->toBeInstanceOf(User::class);
});

it('creates a collection of members and owner', function () {
    $this->team->members()->attach(createUser(states: ['count' => 5]));

    expect($this->team->membersAndOwner())->toBeInstanceOf(Collection::class)
        ->and($this->team->membersAndOwner())->toHaveCount(6)
        ->and($this->team->membersAndOwner())->each->toBeInstanceOf(User::class);
});

it('has many invitations', function () {
    createTeamInvitation(
        attributes: ['team_id' => $this->team->id],
        states: ['count' => 5]
    );

    expect($this->team->invitations)->toBeInstanceOf(Collection::class)
        ->and($this->team->invitations)->toHaveCount(5)
        ->and($this->team->invitations)->each->toBeInstanceOf(TeamInvitation::class);
});

it('has many links', function () {
    createLink(
        attributes: ['team_id' => $this->team->id],
        states: ['count' => 5]
    );

    expect($this->team->links)->toBeInstanceOf(Collection::class)
        ->and($this->team->links)->toHaveCount(5)
        ->and($this->team->links)->each->toBeInstanceOf(Link::class);
});

it('has many link visits', function () {
    $link = createLink(attributes: ['team_id' => $this->team->id]);

    createVisit(
        attributes: ['link_id' => $link->id],
        states: ['count' => 5]
    );

    expect($this->team->linkVisits)->toBeInstanceOf(Collection::class)
        ->and($this->team->linkVisits)->toHaveCount(5)
        ->and($this->team->linkVisits)->each->toBeInstanceOf(Visit::class);
});

it('scopes personal teams', function () {
    createTeam(states: [
        'personalTeam',
        'count' => 5,
    ]);

    expect(Team::personal()->get()->toArray())->each->toHaveKey('personal_team', true);
});

it('scopes non personal teams', function () {
    createTeam(states: ['count' => 5]);

    expect(Team::notPersonal()->get()->toArray())->each->toHaveKey('personal_team', false);
});
