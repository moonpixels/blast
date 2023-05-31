<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamService
{
    /**
     * Create a new team for the given user.
     */
    public function createTeamForUser(User $user, array $input): Team
    {
        $team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'personal_team' => $input['personal_team'] ?? false,
        ]);

        $user->switchTeam($team);

        return $team;
    }

    /**
     * Update the given team.
     */
    public function updateTeam(Team $team, array $input): bool
    {
        return $team->update($input);
    }

    /**
     * Delete the given team.
     */
    public function deleteTeam(Team $team): bool
    {
        if ($team->personal_team) {
            return false;
        }

        return DB::transaction(function () use ($team) {
            $team->users()->where('current_team_id', $team->id)->each(function (User $user) {
                $user->switchTeam($user->personalTeam());
            });

            if ($team->owner->current_team_id === $team->id) {
                $team->owner->switchTeam($team->owner->personalTeam());
            }

            $team->users()->detach();

            return $team->delete();
        });
    }
}
