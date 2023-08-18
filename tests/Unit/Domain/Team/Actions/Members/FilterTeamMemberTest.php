<?php

use App\Domain\Team\Actions\Members\FilterTeamMembers;
use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['email' => 'test@blst.to']);
    $this->team = Team::factory()->create();

    $this->team->members()->attach(User::factory(15)->create());
    $this->team->members()->attach($this->user);
});

it('returns all results if a search term is not sent', function () {
    $members = FilterTeamMembers::run($this->team);

    expect($members->count())->toBe(10);
});

it('returns filtered results if a search term is sent', function () {
    $members = FilterTeamMembers::run($this->team, '@blst.to');

    expect($members->count())->toBe(1);
});

it('returns an empty collection if no results are found', function () {
    $members = FilterTeamMembers::run($this->team, 'membership does not exist');

    expect($members->count())->toBe(0);
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
