<?php

use App\Domain\Team\Actions\Invitations\FilterTeamInvitations;

beforeEach(function () {
    $this->team = createTeam();

    createTeamInvitation(
        attributes: ['team_id' => $this->team->id],
        states: ['count' => 20]
    );
});

it('returns a paginated collection', function () {
    $invitations = FilterTeamInvitations::run($this->team);

    expect($invitations->currentPage())->toBe(1)
        ->and($invitations->perPage())->toBe(10)
        ->and($invitations->hasPages())->toBeTrue()
        ->and($invitations->hasMorePages())->toBeTrue()
        ->and($invitations->lastPage())->toBe(2)
        ->and($invitations->previousPageUrl())->toBeNull();
});

it('filters invitations by a search term', function () {
    createTeamInvitation(attributes: [
        'team_id' => $this->team->id,
        'email' => 'test@example.com',
    ]);

    $invitations = FilterTeamInvitations::run($this->team, 'test@example.com');

    expect($invitations->count())->toBe(1)
        ->and($invitations->first()->email)->toBe('test@example.com');
});

it('returns an empty collection if no results are found', function () {
    $invitations = FilterTeamInvitations::run($this->team, 'invitation does not exist');

    expect($invitations->count())->toBe(0);
});
