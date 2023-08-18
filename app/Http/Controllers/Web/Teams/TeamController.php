<?php

namespace App\Http\Controllers\Web\Teams;

use App\Domain\Team\Actions\CreateTeam;
use App\Domain\Team\Actions\DeleteTeam;
use App\Domain\Team\Actions\Invitations\FilterTeamInvitations;
use App\Domain\Team\Actions\Members\FilterTeamMembers;
use App\Domain\Team\Actions\UpdateTeam;
use App\Domain\Team\Data\TeamData;
use App\Domain\Team\Models\Team;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class TeamController extends Controller
{
    /**
     * Show the team management page.
     */
    public function show(Team $team): Response
    {
        $this->authorize('view', $team);

        $props = [
            'filters' => [
                'view' => request()->query('view', 'members'),
                'query' => request()->query('query'),
            ],
            'team' => TeamResource::createWithoutWrapping($team->load('owner')),
        ];

        if (request()->user()->ownsTeam($team)) {
            if (request()->query('view') === 'invitations') {
                $props['invitations'] = FilterTeamInvitations::run($team, request()->query('query'))
                    ->withQuery(['view' => request()->query('view')]);
            } else {
                $props['members'] = FilterTeamMembers::run($team, request()->query('query'))
                    ->withQuery(['view' => request()->query('view')]);
            }
        }

        return inertia('Teams/Show', $props);
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(TeamData $data): RedirectResponse
    {
        $team = CreateTeam::run(request()->user(), $data);

        return redirect()->route('teams.show', $team);
    }

    /**
     * Update the specified team in storage.
     */
    public function update(Team $team, TeamData $data): RedirectResponse
    {
        $this->authorize('update', $team);

        UpdateTeam::run($team, $data);

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
