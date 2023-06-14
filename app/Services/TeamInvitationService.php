<?php

namespace App\Services;

use App\Exceptions\InvalidTeamMembershipException;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;

class TeamInvitationService
{
    /**
     * Create a new team invitation for the given team.
     */
    public function createInvitationForTeam(Team $team, array $attributes): TeamInvitation
    {
        $invitation = $team->invitations()->create([
            'email' => $attributes['email'],
        ]);

        $invitation->notify(new TeamInvitationNotification($invitation));

        return $invitation;
    }

    /**
     * Accept a team invitation.
     *
     * @throws InvalidTeamMembershipException
     */
    public function acceptInvitation(TeamInvitation $invitation): bool
    {
        $user = User::whereEmail($invitation->email)->firstOrFail();

        if ($user->belongsToTeam($invitation->team)) {
            $invitation->delete();
            throw InvalidTeamMembershipException::alreadyOnTeam();
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

    /**
     * Resend the given team invitation.
     */
    public function resendInvitation(TeamInvitation $invitation): bool
    {
        $invitation->notify(new TeamInvitationNotification($invitation));

        return true;
    }
}
