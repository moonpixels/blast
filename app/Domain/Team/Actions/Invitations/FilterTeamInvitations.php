<?php

namespace App\Domain\Team\Actions\Invitations;

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Http\Resources\TeamInvitationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterTeamInvitations
{
    use AsAction;

    /**
     * Filter the team invitations.
     */
    public function handle(Team $team, string $query = null): ResourceCollection
    {
        return TeamInvitationResource::collection(
            TeamInvitation::search($query)
                ->where('team_id', $team->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        );
    }
}
