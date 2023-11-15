<?php

namespace Domain\Team\Actions;

use Domain\Team\DTOs\TeamData;
use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamAction
{
    use AsAction;

    /**
     * Create a new team.
     */
    public function handle(User $user, TeamData $data): Team
    {
        $team = Team::forceCreate([
            'owner_id' => $user->id,
            'name' => $data->name,
            'personal_team' => $data->personal_team,
        ]);

        $user->switchTeam($team);

        return $team;
    }
}
