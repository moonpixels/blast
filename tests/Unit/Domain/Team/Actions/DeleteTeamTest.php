<?php

use App\Domain\Team\Actions\DeleteTeam;
use App\Domain\Team\Events\TeamDeleted;
use App\Domain\Team\Listeners\DeleteTeamLinks;

beforeEach(function () {
    $this->user = createUser();
});

it('deletes the given team', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    Event::fake();

    expect(DeleteTeam::run($team))->toBeTrue()
        ->and($team)->toBeSoftDeleted();

    Event::assertDispatched(function (TeamDeleted $event) use ($team) {
        return $event->team->is($team);
    });

    Event::assertListening(TeamDeleted::class, DeleteTeamLinks::class);
});

it('switches team members to their personal team', function () {
    $team = getTeamForUser($this->user, 'Member Team');

    $this->user->switchTeam($team);
    $team->owner->switchTeam($team);

    Event::fake();

    expect(DeleteTeam::run($team))->toBeTrue()
        ->and($this->user->fresh()->currentTeam->is($this->user->personalTeam()))->toBeTrue()
        ->and($team->owner->fresh()->currentTeam->is($team->owner->personalTeam()))->toBeTrue();

    Event::assertListening(TeamDeleted::class, DeleteTeamLinks::class);
});

it('does not delete personal teams', function () {
    expect(DeleteTeam::run($this->user->personalTeam()))->toBeFalse()
        ->and($this->user->personalTeam()->exists())->toBeTrue();
});
