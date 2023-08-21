<?php

namespace App\Http\Controllers\Web\Teams;

use App\Domain\Team\Actions\Invitations\AcceptTeamInvitation;
use App\Domain\Team\Actions\Invitations\CreateTeamInvitation;
use App\Domain\Team\Actions\Invitations\DeleteTeamInvitation;
use App\Domain\Team\Actions\Invitations\ResendTeamInvitation;
use App\Domain\Team\Data\TeamInvitationData;
use App\Domain\Team\Exceptions\InvalidTeamMemberException;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class TeamInvitationController extends Controller
{
    /**
     * Create a new team invitation.
     */
    public function store(Team $team, TeamInvitationData $data): RedirectResponse
    {
        $this->authorize('attachAnyMember', $team);

        CreateTeamInvitation::run($team, $data);

        return back()->with('success', [
            'title' => __('Invitation sent'),
            'message' => __('An invitation has been sent to :email.', ['email' => $data->email]),
        ]);
    }

    /**
     * Delete the given team invitation.
     */
    public function destroy(Team $team, TeamInvitation $invitation): RedirectResponse
    {
        $this->authorize('delete', $invitation);

        DeleteTeamInvitation::run($invitation);

        return back()->with('success', [
            'title' => __('Invitation cancelled'),
            'message' => __('The invitation for :email has been cancelled.', ['email' => $invitation->email]),
        ]);
    }

    /**
     * Accept the given team invitation.
     */
    public function accept(Team $team, TeamInvitation $invitation): RedirectResponse
    {
        $this->authorize('accept', $invitation);

        try {
            AcceptTeamInvitation::run($invitation);

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
     * Resend the given team invitation.
     */
    public function resend(Team $team, TeamInvitation $invitation): RedirectResponse
    {
        $this->authorize('resend', $invitation);

        ResendTeamInvitation::run($invitation);

        return back()->with('success', [
            'title' => __('Invitation resent'),
            'message' => __('The invitation for :email has been resent.', ['email' => $invitation->email]),
        ]);
    }
}
