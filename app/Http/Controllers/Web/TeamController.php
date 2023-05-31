<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\StoreRequest;
use App\Http\Requests\Teams\UpdateRequest;
use App\Http\Resources\Team\TeamResource;
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

        return inertia('Teams/Show', [
            'team' => new TeamResource($team, true),
        ]);
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
            'title' => __('teams.team_update_success.title'),
            'message' => __('teams.team_update_success.message', ['team_name' => $team->name]),
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
                'title' => __('teams.team_delete_error.title'),
                'message' => __('teams.team_delete_error.message', ['team_name' => $team->name]),
            ]);
        }

        return redirect()->route('links.index')->with('success', [
            'title' => __('teams.team_delete_success.title'),
            'message' => __('teams.team_delete_success.message', ['team_name' => $team->name]),
        ]);
    }
}
