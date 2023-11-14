<?php

namespace App\Api\Controllers\V1;

use App\Api\QueryBuilders\TeamInvitationListQuery;
use App\Api\Requests\TeamInvitationCreateRequest;
use App\Api\Resources\TeamInvitationResource;
use App\Controller;
use Domain\Team\Actions\Invitations\CreateTeamInvitationAction;
use Domain\Team\Actions\Invitations\DeleteTeamInvitationAction;
use Domain\Team\Models\Team;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamInvitationController extends Controller
{
    /**
     * Retrieve a list of the team's invitations.
     */
    public function index(Team $team, TeamInvitationListQuery $teamInvitationQuery): ResourceCollection
    {
        $this->authorize('viewAnyMember', $team);

        $teamInvitations = $teamInvitationQuery->paginate()->withQueryString();

        return TeamInvitationResource::collection($teamInvitations);
    }

    /**
     * Create a team invitation.
     */
    public function store(TeamInvitationCreateRequest $request, Team $team): JsonResource
    {
        $this->authorize('attachAnyMember', $team);

        $invitation = CreateTeamInvitationAction::run($team, $request->toDTO());

        return TeamInvitationResource::make($invitation->loadMissing('team'));

    }

    /**
     * Retrieve the given team invitation.
     */
    public function show(Team $team, TeamInvitation $invitation): JsonResource
    {
        $this->authorize('view', $invitation);

        return TeamInvitationResource::make($invitation);
    }

    /**
     * Delete the given team invitation.
     */
    public function destroy(Team $team, TeamInvitation $invitation): Response
    {
        $this->authorize('delete', $invitation);

        DeleteTeamInvitationAction::run($invitation);

        return response()->noContent();
    }
}
