<?php

namespace App\Actions\Teams;

use App\Concerns\Actionable;
use App\Http\Resources\TeamInvitation\TeamInvitationResource;
use App\Models\Team;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilterTeamInvitations
{
    use Actionable;

    /**
     * Filter the team invitations.
     */
    public function handle(Team $team, ?string $searchTerm = null): ResourceCollection
    {
        return TeamInvitationResource::collection($team->invitations()
            ->whereEmailLike($searchTerm)
            ->paginate(10)
            ->withQueryString()
        );
    }
}
