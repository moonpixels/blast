<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMembers\StoreRequest;
use App\Models\Team;
use App\Services\TeamMemberService;
use Illuminate\Http\RedirectResponse;

class TeamMemberController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct(protected readonly TeamMemberService $teamMemberService)
    {
    }

    /**
     * Add a new team member to a team.
     */
    public function store(StoreRequest $request, Team $team): RedirectResponse
    {
        $this->teamMemberService->inviteMemberToTeam($team, $request->validated());

        return back()->with('success', [
            'title' => __('Invitation sent'),
            'message' => __('An invitation has been sent to :email.', ['email' => $request->validated()['email']]),
        ]);
    }
}
