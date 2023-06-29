<?php

namespace App\Actions\Teams;

use App\Concerns\Actionable;
use App\Models\TeamInvitation;
use App\Notifications\TeamInvitationNotification;

class ResendTeamInvitation
{
    use Actionable;

    /**
     * Resend the given team invitation.
     */
    public function handle(TeamInvitation $invitation): bool
    {
        $invitation->notify(new TeamInvitationNotification($invitation));

        return true;
    }
}
