<?php

namespace App\Services;

use App\Exceptions\InvalidTeamMemberException;
use App\Models\TeamInvitation;
use App\Models\User;

class TeamInvitationService
{
    /**
     * Accept a team invitation.
     *
     * @throws InvalidTeamMemberException
     */
    public function acceptInvitation(TeamInvitation $invitation): bool
    {
        $user = User::whereEmail($invitation->email)->firstOrFail();

        if ($user->belongsToTeam($invitation->team)) {
            $invitation->delete();
            throw InvalidTeamMemberException::alreadyOnTeam();
        }

        $user->teams()->attach($invitation->team_id);

        $user->switchTeam($invitation->team);

        $invitation->delete();

        return true;
    }

    /**
     * Cancel the given team invitation.
     */
    public function cancelInvitation(TeamInvitation $invitation): bool
    {
        return $invitation->delete();
    }
}
