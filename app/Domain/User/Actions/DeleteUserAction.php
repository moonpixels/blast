<?php

namespace Domain\User\Actions;

use Domain\Team\Actions\DeleteTeamAction;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserAction
{
    use AsAction;

    /**
     * Delete a user.
     */
    public function handle(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->teams()->detach();

            $ownedTeams = $user->ownedTeams;

            $ownedTeams->toQuery()->update(['owner_id' => null]);

            $ownedTeams->each(fn ($team) => DeleteTeamAction::dispatch($team));

            return $user->delete();
        });
    }
}
