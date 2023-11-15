<?php

namespace Domain\Team\Actions\Members;

use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTeamMemberAction
{
    use AsAction;

    /**
     * Delete a team member.
     */
    public function handle(Team $team, User $user): bool
    {
        return DB::transaction(function () use ($team, $user) {
            if ($user->current_team_id === $team->id) {
                $user->switchTeam($user->personalTeam());
            }

            return (bool) $team->members()->detach($user);
        });
    }
}
