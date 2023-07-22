<?php

namespace App\Http\Controllers\Web;

use App\Actions\TeamMemberships\FilterTeamMemberships;
use App\Actions\Teams\CreateTeamForUser;
use App\Actions\Teams\DeleteTeam;
use App\Actions\Teams\FilterTeamInvitations;
use App\Actions\Teams\UpdateTeam;
use App\Data\TeamData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Http\Resources\Team\TeamResource;
use App\Http\Resources\TeamMembership\TeamMembershipResource;
use App\Models\Team;
use App\Models\TeamMembership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class TeamController extends Controller
{
    /**
     * Show the team management page.
     */
    public function show(Request $request, Team $team): Response
    {
        $this->authorize('view', $team);

        $props = [
            'filters' => [
                'view' => $request->query('view', 'members'),
                'query' => $request->query('query'),
            ],
            'team' => TeamResource::createWithoutWrapping($team),
        ];

        if ($request->user()->ownsTeam($team)) {
            if ($request->query('view') === 'invitations') {
                $props['invitations'] = FilterTeamInvitations::run($team, $request->query('query'))
                    ->withQuery(['view' => $request->query('view')]);
            } else {
                $props['memberships'] = FilterTeamMemberships::run($team, $request->query('query'))
                    ->withQuery(['view' => $request->query('view')]);
            }
        } else {
            $membership = TeamMembership::whereUserId($request->user()->id)->whereTeamId($team->id)->first();
            $props['teamMembership'] = TeamMembershipResource::createWithoutWrapping($membership);
        }

        return inertia('Teams/Show', $props);
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $team = CreateTeamForUser::run($request->user(), TeamData::from($request->validated()));

        return redirect()->route('teams.show', $team);
    }

    /**
     * Update the specified team in storage.
     */
    public function update(UpdateRequest $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        UpdateTeam::run($team, TeamData::from($request->validated()));

        return back()->with('success', [
            'title' => __('Team updated'),
            'message' => __(':team_name team has been updated successfully.', ['team_name' => $team->name]),
        ]);
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        if (! DeleteTeam::run($team)) {
            return back()->with('error', [
                'title' => __('Team not deleted'),
                'message' => __('The :team_name team could not be deleted. Personal teams cannot be deleted.',
                    ['team_name' => $team->name]),
            ]);
        }

        return redirect(config('fortify.home'))->with('success', [
            'title' => __('Team deleted'),
            'message' => __(':team_name team has been deleted successfully.', ['team_name' => $team->name]),
        ]);
    }
}
