<?php

namespace App\Web\Controllers;

use App\Api\QueryBuilders\TeamInvitationListQuery;
use App\Api\QueryBuilders\TeamMemberListQuery;
use App\Api\Requests\TeamStoreRequest;
use App\Api\Requests\TeamUpdateRequest;
use App\Api\Resources\TeamInvitationResource;
use App\Api\Resources\TeamResource;
use App\Api\Resources\UserResource;
use App\Controller;
use Domain\Team\Actions\CreateTeamAction;
use Domain\Team\Actions\DeleteTeamAction;
use Domain\Team\Actions\UpdateTeamAction;
use Domain\Team\Models\Team;
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
                'view' => request()->input('view', 'members'),
                'search' => request()->input('filter.search'),
            ],
            'team' => TeamResource::createWithoutWrapping($team->load('owner')),
        ];

        if (request()->user()->ownsTeam($team)) {
            if (request()->query('view') === 'invitations') {
                $invitations = (new TeamInvitationListQuery(request()))->paginate(10)->withQueryString();
                $props['invitations'] = TeamInvitationResource::collection($invitations)->withQuery(['view' => request()->input('view')]);
            } else {
                $members = (new TeamMemberListQuery(request()))->paginate(10)->withQueryString();
                $props['members'] = UserResource::collection($members)->withQuery(['view' => request()->input('view')]);
            }
        }

        return inertia('teams/show', $props);
    }

    /**
     * Create a new team.
     */
    public function store(TeamStoreRequest $request): RedirectResponse
    {
        $team = CreateTeamAction::run($request->user(), $request->toDTO());

        return redirect()->route('teams.show', $team);
    }

    /**
     * Update the given team.
     */
    public function update(TeamUpdateRequest $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        UpdateTeamAction::run($team, $request->toDTO());

        return back()->with('success', [
            'title' => __('Team updated'),
            'message' => __(':team_name team has been updated successfully.', ['team_name' => $team->name]),
        ]);
    }

    /**
     * Delete the given team.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        if (! DeleteTeamAction::run($team)) {
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
