<?php

namespace App\Http\Controllers\Web\Teams;

use App\Domain\Team\Actions\Invitations\CancelTeamInvitation;
use App\Domain\Team\Models\TeamInvitation;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class InvitationController extends Controller
{
    /**
     * Cancel the given team invitation.
     */
    public function destroy(TeamInvitation $invitation): RedirectResponse
    {
        $this->authorize('delete', $invitation);

        CancelTeamInvitation::run($invitation);

        return back()->with('success', [
            'title' => __('Invitation cancelled'),
            'message' => __('The invitation for :email has been cancelled.', ['email' => $invitation->email]),
        ]);
    }
}
