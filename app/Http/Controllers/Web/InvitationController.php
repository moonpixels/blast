<?php

namespace App\Http\Controllers\Web;

use App\Actions\Teams\CancelTeamInvitation;
use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;

class InvitationController extends Controller
{
    /**
     * Cancel the given team invitation.
     */
    public function destroy(TeamInvitation $invitation): RedirectResponse
    {
        $this->authorize('delete', $invitation);

        CancelTeamInvitation::execute($invitation);

        return back()->with('success', [
            'title' => __('Invitation cancelled'),
            'message' => __('The invitation for :email has been cancelled.', ['email' => $invitation->email]),
        ]);
    }
}
