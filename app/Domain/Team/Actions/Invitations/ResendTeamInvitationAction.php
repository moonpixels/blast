<?php

namespace Domain\Team\Actions\Invitations;

use Domain\Team\Mail\TeamInvitationMail;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class ResendTeamInvitationAction
{
    use AsAction;

    /**
     * Resend a team invitation.
     */
    public function handle(TeamInvitation $invitation): bool
    {
        Mail::to($invitation->email)->send(new TeamInvitationMail($invitation));

        return true;
    }
}
