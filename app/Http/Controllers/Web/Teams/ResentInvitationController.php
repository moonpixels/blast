<?php

namespace App\Http\Controllers\Web\Teams;

use App\Domain\Team\Actions\Invitations\ResendTeamInvitation;
use App\Domain\Team\Models\TeamInvitation;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ResentInvitationController extends Controller
{
    /**
     * Resend the given team invitation.
     */
    public function store(): RedirectResponse
    {
        $invitation = TeamInvitation::findOrFail(request()->input('invitation_id'));

        $this->authorize('resend', $invitation);

        ResendTeamInvitation::run($invitation);

        return back()->with('success', [
            'title' => __('Invitation resent'),
            'message' => __('The invitation for :email has been resent.', ['email' => $invitation->email]),
        ]);
    }
}
