<?php

namespace App\Domain\User\Actions;

use App\Domain\Team\Actions\DeleteTeam;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUser
{
    use AsAction;

    /**
     * Delete the given user.
     */
    public function handle(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->teams()->detach();

            $ownedTeams = $user->ownedTeams;

            $ownedTeams->toQuery()->update(['owner_id' => null]);

            $ownedTeams->each(fn ($team) => DeleteTeam::dispatch($team));

            return $user->delete();
        });
    }
}
