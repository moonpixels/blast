<?php

namespace App\Http\Controllers\Web\Teams;

use App\Domain\Team\Actions\Memberships\DeleteTeamMembership;
use App\Domain\Team\Models\TeamMembership;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class TeamMembershipController extends Controller
{
    /**
     * Delete the given team member.
     */
    public function destroy(TeamMembership $teamMembership): RedirectResponse
    {
        $this->authorize('delete', $teamMembership);

        DeleteTeamMembership::run($teamMembership);

        if (request()->user()->ownsTeam($teamMembership->team)) {
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
