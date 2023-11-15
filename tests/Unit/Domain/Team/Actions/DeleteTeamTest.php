<?php

use Domain\Team\Actions\DeleteTeamAction;
use Domain\Team\Events\TeamDeletedEvent;
use Domain\Team\Listeners\DeleteTeamLinks;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->user = createUser();
});

it('deletes the given team', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    Event::fake();

    expect(DeleteTeamAction::run($team))->toBeTrue()
        ->and($team)->toBeSoftDeleted();

    Event::assertListening(TeamDeletedEvent::class, DeleteTeamLinks::class);
});

it('switches team members to their personal team', function () {
    $team = getTeamForUser($this->user, 'Member Team');

    $this->user->switchTeam($team);
    $team->owner->switchTeam($team);

    Event::fake();

    expect(DeleteTeamAction::run($team))->toBeTrue()
        ->and($team->members->count())->toBe(0)
        ->and($this->user->fresh()->currentTeam->is($this->user->personalTeam()))->toBeTrue()
        ->and($team->owner->fresh()->currentTeam->is($team->owner->personalTeam()))->toBeTrue();

    Event::assertListening(TeamDeletedEvent::class, DeleteTeamLinks::class);
});
