<?php

namespace App\Domain\Link\Actions;

use App\Domain\Link\Models\Link;
use App\Domain\Link\Resources\LinkResource;
use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravel\Scout\Builder as ScoutBuilder;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterLinks
{
    use AsAction;

    /**
     * Filter links.
     */
    public function handle(Team $team = null, string $query = null, User $user = null): ResourceCollection
    {
        return LinkResource::collection(
            Link::search($query)
                ->when($team, fn (ScoutBuilder $query) => $query->where('team_id', $team->id))
                ->orderBy('created_at', 'desc')
                ->query(fn (Builder $query) => $query->with('team'))
                ->query(fn (Builder $query) => $query->when($user, fn (Builder $query) => $query->forUser($user)))
                ->paginate(15)
        );
    }
}
