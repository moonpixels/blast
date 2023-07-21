<?php

namespace App\Actions\Links;

use App\Http\Resources\Link\LinkResource;
use App\Models\Link;
use App\Models\Team;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterLinks
{
    use AsAction;

    /**
     * Filter the links.
     */
    public function handle(Team $team, string $searchTerm = null): ResourceCollection
    {
        return LinkResource::collection(
            Link::search($searchTerm)
                ->where('team_id', $team->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString()
        );
    }
}
