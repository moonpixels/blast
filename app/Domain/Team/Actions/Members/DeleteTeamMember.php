<?php

namespace App\Domain\Team\Actions\Members;

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTeamMember
{
    use AsAction;

    /**
     * Delete the given team member.
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
