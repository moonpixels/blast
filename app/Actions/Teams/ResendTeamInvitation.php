<?php

namespace App\Actions\Teams;

use App\Models\TeamInvitation;
use App\Notifications\TeamInvitationNotification;
use Lorisleiva\Actions\Concerns\AsAction;

class ResendTeamInvitation
{
    use AsAction;

    /**
     * Resend the given team invitation.
     */
    public function handle(TeamInvitation $invitation): bool
    {
        $invitation->notify(new TeamInvitationNotification($invitation));

        return true;
    }
}
