<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\InvalidTeamMemberException;
use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use App\Services\TeamInvitationService;
use Illuminate\Http\RedirectResponse;

class TeamInvitationController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct(protected readonly TeamInvitationService $teamInvitationService)
    {
    }

    /**
     * Accept a team invitation.
     */
    public function accept(TeamInvitation $invitation): RedirectResponse
    {
        try {
            $this->teamInvitationService->acceptInvitation($invitation);

            return redirect(config('fortify.home'))->with('success', [
                'title' => __('Invitation accepted'),
                'message' => __('You have been added to the :team team.', ['team' => $invitation->team->name]),
            ]);
        } catch (InvalidTeamMemberException $e) {
            return redirect(config('fortify.home'))->with('error', [
                'title' => __('Invitation failed'),
                'message' => __('You are already on the :team team.', ['team' => $invitation->team->name]),
            ]);
        }
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
