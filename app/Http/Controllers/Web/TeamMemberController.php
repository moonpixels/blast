<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMembership;
use App\Services\TeamMembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
    public function destroy(Request $request, Team $team, TeamMembership $teamMembership): RedirectResponse
    {
        $this->authorize('delete', $teamMembership);

        $this->teamMembershipService->deleteTeamMembership($teamMembership);

        if ($request->user()->ownsTeam($teamMembership->team)) {
            return back()->with('success', [
                'title' => __('Member removed'),
                'message' => __('The member has been removed from the team.'),
            ]);
        }

        return redirect(route('teams.show', $teamMembership->user->current_team_id))->with('success', [
            'title' => __('You have left the team'),
            'message' => __('You have left the :team_name team.', ['team_name' => $teamMembership->team->name]),
        ]);
    }
}
