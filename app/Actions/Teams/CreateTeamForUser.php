<?php

namespace App\Actions\Teams;

use App\Models\Team;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamForUser
{
    use AsAction;

    /**
     * Create a new team for the given user.
     */
    public function handle(User $user, array $attributes): Team
    {
        $team = $user->ownedTeams()->create([
            'name' => $attributes['name'],
            'personal_team' => $attributes['personal_team'] ?? false,
        ]);

        $user->switchTeam($team);

        return $team;
    }
}
