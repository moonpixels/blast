<?php

use App\Domain\Team\Actions\Members\FilterTeamMembers;

beforeEach(function () {
    $this->team = createTeam();

    $this->team->members()->attach(createUser(states: ['count' => 20]));
});

it('returns a paginated collection', function () {
    $members = FilterTeamMembers::run($this->team);

    expect($members->currentPage())->toBe(1)
        ->and($members->perPage())->toBe(10)
        ->and($members->hasPages())->toBeTrue()
        ->and($members->hasMorePages())->toBeTrue()
        ->and($members->lastPage())->toBe(2)
        ->and($members->previousPageUrl())->toBeNull();
});

it('filters members by a search term', function () {
    $this->team->members()->attach(createUser(attributes: ['name' => 'Test User']));

    $members = FilterTeamMembers::run($this->team, 'Test User');

    expect($members->count())->toBe(1)
        ->and($members->first()->name)->toBe('Test User');
});

it('returns an empty collection if no results are found', function () {
    $members = FilterTeamMembers::run($this->team, 'member does not exist');

    expect($members->count())->toBe(0);
});
