<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMembership;
use App\Services\TeamMembershipService;
use Illuminate\Http\RedirectResponse;

class TeamMemberController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct(protected readonly TeamMembershipService $teamMembershipService)
    {
    }

    /**
     * Delete the given team member.
     */
    public function destroy(Team $team, TeamMembership $teamMembership): RedirectResponse
    {
        $this->authorize('delete', $teamMembership);

        $this->teamMembershipService->deleteTeamMembership($teamMembership);

        return back()->with('success', [
            'title' => __('Member removed'),
            'message' => __('The member has been removed from the team.'),
        ]);
    }
}
