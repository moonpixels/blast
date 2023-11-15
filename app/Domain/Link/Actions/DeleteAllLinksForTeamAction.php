<?php

namespace Domain\Link\Actions;

use Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteAllLinksForTeamAction
{
    use AsAction;

    /**
     * Delete all links for a team.
     */
    public function handle(Team $team): bool
    {
        return $team->links()->chunkById(1000, function (Collection $links) {
            $links->toQuery()->update(['deleted_at' => now()]);
        });
    }
}
