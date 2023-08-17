<?php

namespace App\Domain\Team\Actions;

use App\Domain\Team\Data\TeamData;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeam
{
    use AsAction;

    /**
     * Create a new team with the given user as the owner.
     */
    public function handle(User $user, TeamData $data, bool $personalTeam = false): Team
    {
        $team = $user->ownedTeams()->make($data->toArray());

        $team->personal_team = $personalTeam;

        $team->save();

        $user->switchTeam($team);

        return $team;
    }
}
