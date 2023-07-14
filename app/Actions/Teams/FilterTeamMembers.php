<?php

namespace App\Actions\Teams;

use App\Http\Resources\User\UserResource;
use App\Models\Team;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterTeamMembers
{
    use AsAction;

    /**
     * Filter the team members.
     */
    public function handle(Team $team, string $searchTerm = null): ResourceCollection
    {
        return UserResource::collection($team->users()
            ->whereEmailLike($searchTerm)
            ->orWhere(fn ($query) => $query->whereNameLike($searchTerm))
            ->paginate(10)
            ->withQueryString()
        );
    }
}
