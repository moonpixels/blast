<?php

namespace App\Domain\Team\Policies;

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the team.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            || $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can update the team.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            && ! $team->personal_team;
    }

    /**
     * Determine whether the user can attach a team member.
     */
    public function attachAnyMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            && ! $team->personal_team;
    }

    /**
     * Determine whether the user can detach a team member.
     */
    public function detachMember(User $user, Team $team, User $member): bool
    {
        return $user->ownsTeam($team)
            || $user->id === $member->id;
    }
}
