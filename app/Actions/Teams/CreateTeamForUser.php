<?php

namespace App\Actions\Teams;

use App\Data\TeamData;
use App\Models\Team;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamForUser
{
    use AsAction;

    /**
     * Create a new team for the given user.
     */
    public function handle(User $user, TeamData $data): Team
    {
        $team = $user->ownedTeams()->create([
            'name' => $data->name,
            'personal_team' => $data->personalTeam,
        ]);

        $user->switchTeam($team);

        return $team;
    }
}
