<?php

namespace App\Http\Controllers\Web\Teams;

use App\Actions\Teams\AcceptTeamInvitation;
use App\Exceptions\InvalidTeamMembershipException;
use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;

class AcceptedInvitationController extends Controller
{
    /**
     * Accept a team invitation.
     */
    public function show(TeamInvitation $invitation): RedirectResponse
    {
        try {
            AcceptTeamInvitation::run($invitation);

            return redirect(config('fortify.home'))->with('success', [
                'title' => __('Invitation accepted'),
                'message' => __('You have been added to the :team team.', ['team' => $invitation->team->name]),
            ]);
        } catch (InvalidTeamMembershipException $e) {
            return redirect(config('fortify.home'))->with('error', [
                'title' => __('Invitation failed'),
                'message' => __('You are already on the :team team.', ['team' => $invitation->team->name]),
            ]);
        }
    }
}
