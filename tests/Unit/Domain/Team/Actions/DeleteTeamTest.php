<?php

use App\Domain\Link\Actions\DeleteLinksForTeam;
use App\Domain\Team\Actions\DeleteTeam;

beforeEach(function () {
    $this->user = createUser();
});

it('deletes the given team', function () {
    $team = getTeamForUser($this->user, 'Owned Team');

    Queue::fake();

    expect(DeleteTeam::run($team))->toBeTrue()
        ->and($team)->toBeSoftDeleted();

    DeleteLinksForTeam::assertPushed(1);
});

it('switches team members to their personal team', function () {
    $team = getTeamForUser($this->user, 'Member Team');

    $this->user->switchTeam($team);
    $team->owner->switchTeam($team);

    //    expect(DeleteTeam::run($team))->toBeTrue()
    //        ->and($this->user->fresh()->currentTeam->is($this->user->personalTeam()))->toBeTrue()
    //        ->and($team->owner->currentTeam->is($team->owner->personalTeam()))->toBeTrue();
});

it('does not delete personal teams', function () {
    expect(DeleteTeam::run($this->user->personalTeam()))->toBeFalse()
        ->and($this->user->personalTeam()->exists())->toBeTrue();
});
