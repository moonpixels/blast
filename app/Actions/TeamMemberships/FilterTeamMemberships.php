<?php

namespace App\Actions\TeamMemberships;

use App\Http\Resources\TeamMembership\TeamMembershipResource;
use App\Models\Team;
use App\Models\TeamMembership;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravel\Scout\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterTeamMemberships
{
    use AsAction;

    /**
     * Filter the team members.
     */
    public function handle(Team $team, string $query = null): ResourceCollection
    {
        return TeamMembershipResource::collection(
            TeamMembership::search($query)
                ->where('team_id', $team->id)
                ->when(config('scout.driver') === 'meilisearch',
                    fn (Builder $query) => $query->orderBy('user_name'))
                ->paginate(10)
        );
    }
}
