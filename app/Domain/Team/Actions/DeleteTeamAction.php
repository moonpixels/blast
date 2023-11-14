<?php

namespace Domain\Team\Actions;

use Domain\Team\Events\TeamDeletedEvent;
use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTeamAction
{
    use AsAction;

    /**
     * Delete a team.
     */
    public function handle(Team $team): bool
    {
        DB::transaction(function () use ($team) {
            if (! $team->personal_team) {
                User::query()
                    ->where('current_team_id', $team->id)
                    ->each(function (User $user) {
                        $user->switchTeam($user->personalTeam());
                    });
            }

            $team->members()->detach();

            $team->delete();
        });

        TeamDeletedEvent::dispatch($team);

        return true;
    }
}
