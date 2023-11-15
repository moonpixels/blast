<?php

namespace Domain\Team\Actions;

use Domain\Team\DTOs\TeamData;
use Domain\Team\Models\Team;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTeamAction
{
    use AsAction;

    /**
     * Update a team.
     */
    public function handle(Team $team, TeamData $data): bool
    {
        return $team->update([
            'name' => $data->name,
        ]);
    }
}
