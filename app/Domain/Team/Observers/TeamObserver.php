<?php

namespace App\Domain\Team\Observers;

use App\Domain\Link\Actions\DeleteLinksForTeam;
use App\Domain\Team\Models\Team;

class TeamObserver
{
    /**
     * Handle the team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        DeleteLinksForTeam::dispatch($team);
    }
}
