<?php

namespace App\Domain\Link\Actions;

use App\Domain\Team\Models\Team;
use App\Support\Concerns\HasUrlInput;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteLinksForTeam
{
    use AsAction, HasUrlInput;

    /**
     * Delete all links for the given team.
     */
    public function handle(Team $team): int
    {
        return $team->links()->delete();
    }
}
