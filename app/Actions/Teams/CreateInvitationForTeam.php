<?php

namespace App\Actions\Teams;

use App\Concerns\Actionable;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Notifications\TeamInvitationNotification;

class CreateInvitationForTeam
{
    use Actionable;

    /**
     * Create a new team invitation for the given team.
     */
    public function handle(Team $team, array $attributes): TeamInvitation
    {
        $invitation = $team->invitations()->create([
            'email' => $attributes['email'],
        ]);

        $invitation->notify(new TeamInvitationNotification($invitation));

        return $invitation;
    }
}
