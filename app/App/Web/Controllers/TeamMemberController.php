<?php

namespace App\Web\Controllers;

use App\Controller;
use Domain\Team\Actions\Members\DeleteTeamMemberAction;
use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Http\RedirectResponse;

class TeamMemberController extends Controller
{
    /**
     * Delete the given team member.
     */
    public function destroy(Team $team, User $member): RedirectResponse
    {
        $this->authorize('detachMember', [$team, $member]);

        DeleteTeamMemberAction::run($team, $member);

        if (request()->user()->ownsTeam($team)) {
            return back()->with('success', [
                'title' => __('Member removed'),
                'message' => __('The member has been removed from the team.'),
            ]);
        }

        return redirect(route('teams.show', request()->user()->personalTeam()))->with('success', [
            'title' => __('You have left the team'),
            'message' => __('You have left the :team_name team.', ['team_name' => $team->name]),
        ]);
    }
}
