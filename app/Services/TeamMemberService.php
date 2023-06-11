<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Notifications\TeamInvitationNotification;

class TeamMemberService
{
    /**
     * Invite a new team member to the given team.
     */
    public function inviteMemberToTeam(Team $team, array $attributes): TeamInvitation
    {
        $invitation = $team->invitations()->create([
            'email' => $attributes['email'],
        ]);

        $invitation->notify(new TeamInvitationNotification($invitation));

        return $invitation;
    }
}
