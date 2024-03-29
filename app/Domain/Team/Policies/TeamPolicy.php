<?php

namespace Domain\Team\Policies;

use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any teams.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the team.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            || $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can create teams.
     */
    public function create(User $user): bool
    {
        return true;
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
     * Determine whether the user view any team members.
     */
    public function viewAnyMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can view the team member.
     */
    public function viewMember(User $user, Team $team, User $member): bool
    {
        return $user->ownsTeam($team)
            || $user->is($member);
    }

    /**
     * Determine whether the user can add team members.
     */
    public function attachAnyMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team)
            && ! $team->personal_team;
    }

    /**
     * Determine whether the user can remove team members.
     */
    public function detachMember(User $user, Team $team, User $member): bool
    {
        return $user->ownsTeam($team)
            || $user->is($member);
    }
}
