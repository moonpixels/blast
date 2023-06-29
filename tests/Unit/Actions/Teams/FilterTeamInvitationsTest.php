<?php

use App\Actions\Teams\FilterTeamInvitations;
use App\Models\Team;
use App\Models\TeamInvitation;

beforeEach(function () {
    $this->team = Team::factory()->create();

    TeamInvitation::factory(15)->for($this->team)->create();
    TeamInvitation::factory()->for($this->team)->create(['email' => 'john.doe@blst.to']);
    TeamInvitation::factory()->create(['email' => 'john.doe@blst.to']);
});

it('returns all results if a search term is not sent', function () {
    $invitations = FilterTeamInvitations::execute($this->team);

    expect($invitations->count())->toBe(10);
});

it('returns filtered results if a search term is sent', function () {
    $invitations = FilterTeamInvitations::execute($this->team, '@blst.to');

    expect($invitations->count())->toBe(1);
});

it('returns an empty collection if no results are found', function () {
    $invitations = FilterTeamInvitations::execute($this->team, 'invitation does not exist');

    expect($invitations->count())->toBe(0);
});

it('returns a paginated collection', function () {
    $invitations = FilterTeamInvitations::execute($this->team);

    expect($invitations->currentPage())->toBe(1)
        ->and($invitations->perPage())->toBe(10)
        ->and($invitations->hasPages())->toBeTrue()
        ->and($invitations->hasMorePages())->toBeTrue()
        ->and($invitations->lastPage())->toBe(2)
        ->and($invitations->previousPageUrl())->toBeNull();
});
