<?php

namespace App\Actions\Teams;

use App\Http\Resources\TeamInvitation\TeamInvitationResource;
use App\Models\Team;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterTeamInvitations
{
    use AsAction;

    /**
     * Filter the team invitations.
     */
    public function handle(Team $team, string $searchTerm = null): ResourceCollection
    {
        return TeamInvitationResource::collection($team->invitations()
            ->whereEmailLike($searchTerm)
            ->fastPaginate(10)
            ->withQueryString()
        );
    }
}
