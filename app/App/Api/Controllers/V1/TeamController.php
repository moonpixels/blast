<?php

namespace App\Api\Controllers\V1;

use App\Api\Requests\TeamStoreRequest;
use App\Api\Requests\TeamUpdateRequest;
use App\Api\Resources\TeamResource;
use App\Controller;
use Domain\Team\Actions\CreateTeamAction;
use Domain\Team\Actions\DeleteTeamAction;
use Domain\Team\Actions\UpdateTeamAction;
use Domain\Team\Models\Team;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    /**
     * Retrieve a list of teams.
     */
    public function index(): ResourceCollection
    {
        $this->authorize('viewAny', Team::class);

        return TeamResource::collection(request()->user()->allTeams());
    }

    /**
     * Create a new team.
     */
    public function store(TeamStoreRequest $request): JsonResource
    {
        $this->authorize('create', Team::class);

        $team = CreateTeamAction::run($request->user(), $request->toDTO());

        return TeamResource::make($team);
    }

    /**
     * Retrieve the given team.
     */
    public function show(Team $team): JsonResource
    {
        $this->authorize('view', $team);

        return TeamResource::make($team);
    }

    /**
     * Update the given team.
     */
    public function update(TeamUpdateRequest $request, Team $team): JsonResource
    {
        $this->authorize('update', $team);

        UpdateTeamAction::run($team, $request->toDTO());

        return TeamResource::make($team);
    }

    /**
     * Delete the given team.
     */
    public function destroy(Team $team): Response
    {
        $this->authorize('delete', $team);

        DeleteTeamAction::run($team);

        return response()->noContent();
    }
}
