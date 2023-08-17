<?php

namespace App\Domain\Team\Actions\Invitations;

use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Notifications\TeamInvitationNotification;
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
