<?php

use App\Domain\Team\Events\TeamDeleted;

it('deletes a teams links when the team has been deleted', function () {
    $team = createTeam();

    createLink(
        attributes: ['team_id' => $team->id],
        states: ['count' => 20]
    );

    TeamDeleted::dispatch($team);

    expect($team->links()->withTrashed()->get())->each->toBeSoftDeleted();
});
