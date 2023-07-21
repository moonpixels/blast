<?php

namespace App\Actions\Links;

use App\Http\Resources\Link\LinkResource;
use App\Models\Team;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterLinks
{
    use AsAction;

    /**
     * Filter the links.
     */
    public function handle(Team $team): ResourceCollection
    {
        return LinkResource::collection($team->links()
            ->latest()
            ->fastPaginate(15)
            ->withQueryString()
        );
    }
}
