<?php

namespace App\Domain\Team\Policies;

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can edit the team.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id
            || $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can update the team.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id;
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id && ! $team->personal_team;
    }

    /**
     * Determine whether the user can invite a team member.
     */
    public function inviteMember(User $user, Team $team): bool
    {
        return $user->id === $team->owner_id && ! $team->personal_team;
    }
}
