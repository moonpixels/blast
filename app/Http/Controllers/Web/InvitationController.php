<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use App\Services\TeamInvitationService;
use Illuminate\Http\RedirectResponse;

class InvitationController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct(protected readonly TeamInvitationService $teamInvitationService)
    {
    }

    /**
     * Cancel the given team invitation.
     */
    public function destroy(TeamInvitation $invitation): RedirectResponse
    {
        $this->authorize('delete', $invitation);

        $this->teamInvitationService->cancelInvitation($invitation);

        return back()->with('success', [
            'title' => __('Invitation cancelled'),
            'message' => __('The invitation for :email has been cancelled.', ['email' => $invitation->email]),
        ]);
    }
}
