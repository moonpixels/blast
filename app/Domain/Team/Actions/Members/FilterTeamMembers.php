<?php

namespace App\Domain\Team\Actions\Members;

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use App\Domain\User\Resources\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterTeamMembers
{
    use AsAction;

    /**
     * Filter the team members.
     */
    public function handle(Team $team, string $query = null): ResourceCollection
    {
        return UserResource::collection(
            User::search($query)
                ->whereIn('id', $team->members()->pluck('id')->toArray())
                ->orderBy('name')
                ->paginate(10)
        );
    }
}
