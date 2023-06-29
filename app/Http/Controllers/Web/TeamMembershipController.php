<?php

namespace App\Http\Controllers\Web;

use App\Actions\Teams\DeleteTeamMembership;
use App\Http\Controllers\Controller;
use App\Models\TeamMembership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamMembershipController extends Controller
{
    /**
     * Delete the given team member.
     */
    public function destroy(Request $request, TeamMembership $teamMembership): RedirectResponse
    {
        $this->authorize('delete', $teamMembership);

        DeleteTeamMembership::execute($teamMembership);

        if ($request->user()->ownsTeam($teamMembership->team)) {
            return back()->with('success', [
                'title' => __('Member removed'),
                'message' => __('The member has been removed from the team.'),
            ]);
        }

        return redirect(route('teams.show', $teamMembership->user->currentTeam))->with('success', [
            'title' => __('You have left the team'),
            'message' => __('You have left the :team_name team.', ['team_name' => $teamMembership->team->name]),
        ]);
    }
}
