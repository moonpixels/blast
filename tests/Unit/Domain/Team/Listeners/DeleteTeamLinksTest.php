<?php

use Domain\Team\Events\TeamDeletedEvent;

it('deletes a teams links when the team has been deleted', function () {
    $team = createTeam();

    createLink(states: [
        'for' => $team,
        'count' => 20,
    ]);

    TeamDeletedEvent::dispatch($team);

    $links = $team->links()->withTrashed()->get();

    expect($links)->toHaveCount(20)
        ->and($links)->each->toBeSoftDeleted();
});
