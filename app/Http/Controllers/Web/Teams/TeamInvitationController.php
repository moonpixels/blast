<?php

namespace App\Http\Controllers\Web\Teams;

use App\Domain\Team\Actions\Invitations\CreateInvitationForTeam;
use App\Domain\Team\Data\TeamInvitationData;
use App\Domain\Team\Models\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class TeamInvitationController extends Controller
{
    /**
     * Invite a new member to the given team.
     */
    public function store(Team $team, TeamInvitationData $data): RedirectResponse
    {
        $this->authorize('inviteMember', $team);

        CreateInvitationForTeam::run($team, $data);

        return back()->with('success', [
            'title' => __('Invitation sent'),
            'message' => __('An invitation has been sent to :email.', ['email' => $data->email]),
        ]);
    }
}
