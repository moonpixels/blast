<?php

namespace App\Actions\Teams;

use App\Data\TeamData;
use App\Models\Team;
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
