<?php

namespace App\Actions\Teams;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Notifications\TeamInvitationNotification;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateInvitationForTeam
{
    use AsAction;

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
