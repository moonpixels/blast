<?php

namespace App\Domain\Team\Actions;

use App\Domain\Team\Events\TeamDeleted;
use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
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

        $deleted = DB::transaction(function () use ($team) {
            User::where('current_team_id', $team->id)->each(function (User $user) {
                $user->switchTeam($user->personalTeam());
            });

            $team->members()->detach();

            return $team->delete();
        });

        TeamDeleted::dispatchIf($deleted, $team);

        return $deleted;
    }
}
