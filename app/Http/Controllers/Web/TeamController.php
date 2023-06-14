<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Http\Resources\Team\TeamResource;
use App\Http\Resources\TeamInvitation\TeamInvitationResource;
use App\Http\Resources\User\UserResource;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class TeamController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct(protected readonly TeamService $teamService)
    {
    }

    /**
     * Show the team management page.
     */
    public function show(Request $request, Team $team): Response
    {
        $this->authorize('view', $team);

        $props = [
            'team' => new TeamResource($team, true),
        ];

        if ($request->user()->ownsTeam($team)) {
            $props['members'] = UserResource::collection($team->users()->paginate(10));
            $props['invitations'] = TeamInvitationResource::collection($team->invitations()->paginate(10));
        } else {
            $props['teamMembership'] = $team->users()->where('user_id', $request->user()->id)->first()->team_membership;
        }

        return inertia('Teams/Show', $props);
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $team = $this->teamService->createTeamForUser($request->user(), $request->validated());

        $request->user()->switchTeam($team);

        return redirect()->route('teams.show', $team);
    }

    /**
     * Update the specified team in storage.
     */
    public function update(UpdateRequest $request, Team $team): RedirectResponse
    {
        $this->teamService->updateTeam($team, $request->validated());

        return back()->with('success', [
            'title' => __('Team updated'),
            'message' => __(':team_name team has been updated successfully.', ['team_name' => $team->name]),
        ]);
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy(Request $request, Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        if (! $this->teamService->deleteTeam($team)) {
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
