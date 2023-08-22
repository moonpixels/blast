<?php

namespace App\Domain\Team\Listeners;

use App\Domain\Link\Actions\DeleteLinksForTeam;
use App\Domain\Team\Events\TeamDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteTeamLinks implements ShouldQueue
{
    /**
     * Delete all the links for the given team.
     */
    public function handle(TeamDeleted $event): void
    {
        DeleteLinksForTeam::run($event->team);
    }
}
