<?php

namespace App\Domain\Team\Actions;

use App\Domain\Team\Data\TeamData;
use App\Domain\Team\Models\Team;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTeam
{
    use AsAction;

    /**
     * Update the given team.
     */
    public function handle(Team $team, TeamData $data): bool
    {
        return $team->update([
            'name' => $data->name,
        ]);
    }
}
