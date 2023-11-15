<?php

namespace Domain\Team\Actions\Members;

use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamMemberAction
{
    use AsAction;

    /**
     * Create a team member.
     */
    public function handle(Team $team, User $user): bool
    {
        $team->members()->attach($user);

        return true;
    }
}
