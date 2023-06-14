<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamInvitation\StoreRequest;
use App\Models\Team;
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
     * Invite a new member to the given team.
     */
    public function store(StoreRequest $request, Team $team): RedirectResponse
    {
        $this->teamInvitationService->createInvitationForTeam($team, $request->validated());

        return back()->with('success', [
            'title' => __('Invitation sent'),
            'message' => __('An invitation has been sent to :email.', ['email' => $request->validated()['email']]),
        ]);
    }
}
