<?php

namespace App\Domain\Team\Actions\Invitations;

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Resources\TeamInvitationResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterTeamInvitations
{
    use AsAction;

    /**
     * Filter the team invitations.
     */
    public function handle(Team $team, string $query = null, int $perPage = 10): ResourceCollection
    {
        return TeamInvitationResource::collection(
            TeamInvitation::search($query)
                ->where('team_id', $team->id)
                ->orderBy('created_at', 'desc')
                ->query(fn (Builder $query) => $query->with('team'))
                ->paginate($perPage)
        );
    }
}
