<?php

namespace App\Domain\Team\Actions\Memberships;

use App\Domain\Team\Models\TeamMembership;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTeamMembership
{
    use AsAction;

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
