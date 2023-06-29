<?php

use App\Actions\Teams\FilterTeamMembers;
use App\Models\Team;
use App\Models\TeamMembership;
use App\Models\User;

beforeEach(function () {
    $this->team = Team::factory()->create();

    TeamMembership::factory(15)->for($this->team)->create();
    TeamMembership::factory()->for($this->team)->for(User::factory()->create(['email' => 'john.doe@blst.to']))->create();
    TeamMembership::factory()->for(User::whereEmail('john.doe@blst.to')->first())->create();
});

it('returns all results if a search term is not sent', function () {
    $memberships = FilterTeamMembers::execute($this->team);

    expect($memberships->count())->toBe(10);
});

it('returns filtered results if a search term is sent', function () {
    $memberships = FilterTeamMembers::execute($this->team, '@blst.to');

    expect($memberships->count())->toBe(1);
});

it('returns an empty collection if no results are found', function () {
    $memberships = FilterTeamMembers::execute($this->team, 'membership does not exist');

    expect($memberships->count())->toBe(0);
});

it('returns a paginated collection', function () {
    $memberships = FilterTeamMembers::execute($this->team);

    expect($memberships->currentPage())->toBe(1)
        ->and($memberships->perPage())->toBe(10)
        ->and($memberships->hasPages())->toBeTrue()
        ->and($memberships->hasMorePages())->toBeTrue()
        ->and($memberships->lastPage())->toBe(2)
        ->and($memberships->previousPageUrl())->toBeNull();
});
