<?php

namespace App\Domain\Link\Actions;

use App\Domain\Link\Models\Link;
use App\Domain\Link\Resources\LinkResource;
use App\Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterLinks
{
    use AsAction;

    /**
     * Filter links for the given team.
     */
    public function handle(Team $team, string $query = null): ResourceCollection
    {
        return LinkResource::collection(
            Link::search($query)
                ->where('team_id', $team->id)
                ->orderBy('created_at', 'desc')
                ->query(fn (Builder $query) => $query->with('team'))
                ->paginate(15)
        );
    }
}
