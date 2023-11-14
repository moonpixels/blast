<?php

namespace Domain\Team\Listeners;

use Domain\Link\Actions\DeleteAllLinksForTeamAction;
use Domain\Team\Events\TeamDeletedEvent;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteTeamLinks implements ShouldHandleEventsAfterCommit, ShouldQueue
{
    /**
     * Delete all links for a team.
     */
    public function handle(TeamDeletedEvent $event): void
    {
        DeleteAllLinksForTeamAction::run($event->team);
    }
}
