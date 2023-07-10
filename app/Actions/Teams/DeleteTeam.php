<?php

namespace App\Actions\Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTeam
{
    use AsAction;

    /**
     * Delete the given team.
     */
    public function handle(Team $team): bool
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
