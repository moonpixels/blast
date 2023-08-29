<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Team\Actions\Members\DeleteTeamMember;
use App\Domain\Team\Actions\Members\FilterTeamMembers;
use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamMemberController extends Controller
{
    /**
     * Display a list of members for the given team.
     */
    public function index(Team $team): ResourceCollection
    {
        $this->authorize('viewAnyMember', $team);

        return FilterTeamMembers::run($team, request('query'));
    }

    /**
     * Display the given team member.
     */
    public function show(Team $team, User $member): JsonResource
    {
        $this->authorize('viewMember', [$team, $member]);

        return JsonResource::make($member);
    }

    /**
     * Remove the given member from the given team.
     */
    public function destroy(Team $team, User $member): Response
    {
        $this->authorize('detachMember', [$team, $member]);

        DeleteTeamMember::run($team, $member);

        return response()->noContent();
    }
}
