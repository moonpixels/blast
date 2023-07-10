<?php

namespace App\Actions\Teams;

use App\Models\TeamInvitation;
use Lorisleiva\Actions\Concerns\AsAction;

class CancelTeamInvitation
{
    use AsAction;

    /**
     * Cancel the given team invitation.
     */
    public function handle(TeamInvitation $invitation): bool
    {
        return $invitation->delete();
    }
}
