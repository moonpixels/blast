<?php

namespace App\Services;

use App\Models\TeamMembership;
use Illuminate\Support\Facades\DB;

class TeamMembershipService
{
    /**
     * Delete the given team membership.
     */
    public function deleteTeamMembership(TeamMembership $teamMembership): bool
    {
        return DB::transaction(function () use ($teamMembership) {
            if ($teamMembership->user->current_team_id === $teamMembership->team_id) {
                $teamMembership->user->switchTeam($teamMembership->user->personalTeam());
            }

            return $teamMembership->delete();
        });
    }
}
