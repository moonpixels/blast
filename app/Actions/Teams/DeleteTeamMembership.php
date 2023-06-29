<?php

namespace App\Actions\Teams;

use App\Concerns\Actionable;
use App\Models\TeamMembership;
use Illuminate\Support\Facades\DB;

class DeleteTeamMembership
{
    use Actionable;

    /**
     * Delete the given team membership.
     */
    public function handle(TeamMembership $teamMembership): bool
    {
        return DB::transaction(function () use ($teamMembership) {
            if ($teamMembership->user->current_team_id === $teamMembership->team_id) {
                $teamMembership->user->switchTeam($teamMembership->user->personalTeam());
            }

            return (bool) $teamMembership->delete();
        });
    }
}
