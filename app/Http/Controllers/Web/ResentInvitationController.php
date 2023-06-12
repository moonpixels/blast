<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use App\Services\TeamInvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResentInvitationController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct(protected readonly TeamInvitationService $teamInvitationService)
    {
    }

    /**
     * Resend the given team invitation.
     */
    public function store(Request $request): RedirectResponse
    {
        $invitation = TeamInvitation::findOrFail($request->input('invitation_id'));
        
        $this->authorize('resend', $invitation);

        $this->teamInvitationService->resendInvitation($invitation);

        return back()->with('success', [
            'title' => __('Invitation resent'),
            'message' => __('The invitation for :email has been resent.', ['email' => $invitation->email]),
        ]);
    }
}
