<?php

namespace App\Domain\Team\Policies;

use App\Domain\Team\Models\TeamMembership;
use App\Domain\Team\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamMembershipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the team membership.
     */
    public function delete(User $user, TeamMembership $teamMembership): bool
    {
        return $teamMembership->team->owner_id === $user->id
            || $teamMembership->user_id === $user->id;
    }
}
