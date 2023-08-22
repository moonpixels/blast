<?php

namespace App\Domain\Link\Actions;

use App\Domain\Team\Models\Team;
use App\Support\Concerns\HasUrlInput;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteLinksForTeam
{
    use AsAction, HasUrlInput;

    /**
     * Delete all links for the given team.
     */
    public function handle(Team $team): bool
    {
        return $team->links()->chunkById(1000, function (Collection $links) {
            $links->toQuery()->update(['deleted_at' => now()]);
        });
    }
}
