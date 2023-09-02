<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Team\Actions\Invitations\CreateTeamInvitation;
use App\Domain\Team\Actions\Invitations\DeleteTeamInvitation;
use App\Domain\Team\Actions\Invitations\FilterTeamInvitations;
use App\Domain\Team\Data\TeamInvitationData;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Resources\TeamInvitationResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamInvitationController extends Controller
{
    /**
     * Display a list of the team invitations.
     */
    public function index(Team $team): ResourceCollection
    {
        $this->authorize('viewAnyMember', $team);

        return FilterTeamInvitations::run($team, request('query'), request('perPage', 10));
    }

    /**
     * Store a newly created team invitation in storage.
     */
    public function store(Team $team, TeamInvitationData $data): JsonResource
    {
        $this->authorize('attachAnyMember', $team);

        $invitation = CreateTeamInvitation::run($team, $data);

        return TeamInvitationResource::make($invitation->loadMissing('team'));

    }

    /**
     * Display the given team invitation.
     */
    public function show(Team $team, TeamInvitation $invitation): JsonResource
    {
        $this->authorize('view', $invitation);

        return TeamInvitationResource::make($invitation);
    }

    /**
     * Remove the given team invitation from storage.
     */
    public function destroy(Team $team, TeamInvitation $invitation): Response
    {
        $this->authorize('delete', $invitation);

        DeleteTeamInvitation::run($invitation);

        return response()->noContent();
    }
}
