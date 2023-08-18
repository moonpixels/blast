<?php

namespace App\Domain\Team\Actions\Members;

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTeamMember
{
    use AsAction;

    /**
     * Create a new team member.
     */
    public function handle(Team $team, User $user): bool
    {
        $team->members()->attach($user);

        return true;
    }
}
