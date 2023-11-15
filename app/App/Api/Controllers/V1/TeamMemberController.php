<?php

namespace App\Api\Controllers\V1;

use App\Api\QueryBuilders\TeamMemberListQuery;
use App\Api\Resources\UserResource;
use App\Controller;
use Domain\Team\Actions\Members\DeleteTeamMemberAction;
use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamMemberController extends Controller
{
    /**
     * Retrieve a list of the team's members.
     */
    public function index(Team $team, TeamMemberListQuery $teamMemberQuery): ResourceCollection
    {
        $this->authorize('viewAnyMember', $team);

        $members = $teamMemberQuery->paginate()->withQueryString();

        return UserResource::collection($members);
    }

    /**
     * Retrieve the given team member.
     */
    public function show(Team $team, User $member): JsonResource
    {
        $this->authorize('viewMember', [$team, $member]);

        return JsonResource::make($member);
    }

    /**
     * Delete the given team member.
     */
    public function destroy(Team $team, User $member): Response
    {
        $this->authorize('detachMember', [$team, $member]);

        DeleteTeamMemberAction::run($team, $member);

        return response()->noContent();
    }
}
