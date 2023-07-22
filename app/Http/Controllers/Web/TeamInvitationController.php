<?php

namespace App\Http\Controllers\Web;

use App\Actions\Teams\CreateInvitationForTeam;
use App\Data\TeamInvitationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamInvitation\StoreRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;

class TeamInvitationController extends Controller
{
    /**
     * Invite a new member to the given team.
     */
    public function store(StoreRequest $request, Team $team): RedirectResponse
    {
        $this->authorize('inviteMember', $team);

        CreateInvitationForTeam::run($team, TeamInvitationData::from($request->validated()));

        return back()->with('success', [
            'title' => __('Invitation sent'),
            'message' => __('An invitation has been sent to :email.', ['email' => $request->validated()['email']]),
        ]);
    }
}
