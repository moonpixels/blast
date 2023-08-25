<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Team\Actions\CreateTeam;
use App\Domain\Team\Actions\DeleteTeam;
use App\Domain\Team\Actions\UpdateTeam;
use App\Domain\Team\Data\TeamData;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Resources\TeamResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    /**
     * Display a listing of teams.
     */
    public function index(): ResourceCollection
    {
        $this->authorize('viewAny', Team::class);

        return TeamResource::collection(request()->user()->allTeams());
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(TeamData $data): JsonResource
    {
        $this->authorize('create', Team::class);

        $team = CreateTeam::run(request()->user(), $data);

        return TeamResource::make($team);
    }

    /**
     * Display the given team.
     */
    public function show(Team $team): JsonResource
    {
        $this->authorize('view', $team);

        return TeamResource::make($team);
    }

    /**
     * Update the given team in storage.
     */
    public function update(Team $team, TeamData $data): JsonResource
    {
        $this->authorize('update', $team);

        UpdateTeam::run($team, $data);

        return TeamResource::make($team);
    }

    /**
     * Remove the given team from storage.
     */
    public function destroy(Team $team): Response
    {
        $this->authorize('delete', $team);

        DeleteTeam::run($team);

        return response()->noContent();
    }
}
