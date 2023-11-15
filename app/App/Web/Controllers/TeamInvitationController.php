<?php

namespace App\Web\Controllers;

use App\Api\Requests\TeamInvitationCreateRequest;
use App\Controller;
use Domain\Team\Actions\Invitations\AcceptTeamInvitationAction;
use Domain\Team\Actions\Invitations\CreateTeamInvitationAction;
use Domain\Team\Actions\Invitations\DeleteTeamInvitationAction;
use Domain\Team\Actions\Invitations\ResendTeamInvitationAction;
use Domain\Team\Exceptions\InvalidTeamMemberException;
use Domain\Team\Models\Team;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;

class TeamInvitationController extends Controller
{
    /**
     * Create a new team invitation.
     */
    public function store(TeamInvitationCreateRequest $request, Team $team): RedirectResponse
    {
        $this->authorize('attachAnyMember', $team);

        CreateTeamInvitationAction::run($team, $request->toDTO());

        return back()->with('success', [
            'title' => __('Invitation sent'),
            'message' => __('An invitation has been sent to :email.', ['email' => $request->validated('email')]),
        ]);
    }

    /**
     * Delete the given team invitation.
     */
    public function destroy(Team $team, TeamInvitation $invitation): RedirectResponse
    {
        $this->authorize('delete', $invitation);

        DeleteTeamInvitationAction::run($invitation);

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
            AcceptTeamInvitationAction::run($invitation);

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

        ResendTeamInvitationAction::run($invitation);

        return back()->with('success', [
            'title' => __('Invitation resent'),
            'message' => __('The invitation for :email has been resent.', ['email' => $invitation->email]),
        ]);
    }
}
