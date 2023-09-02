<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Link\Actions\CreateLink;
use App\Domain\Link\Actions\DeleteLink;
use App\Domain\Link\Actions\FilterLinks;
use App\Domain\Link\Actions\UpdateLink;
use App\Domain\Link\Data\LinkData;
use App\Domain\Link\Models\Link;
use App\Domain\Link\Resources\LinkResource;
use App\Domain\Team\Models\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    /**
     * Display a list of links.
     */
    public function index(): ResourceCollection
    {
        $this->authorize('viewAny', Link::class);

        if (request('team_id')) {
            $team = Team::find(request('team_id'));

            $this->authorize('view', $team);
        }

        return FilterLinks::run(
            team: $team ?? null,
            query: request('query'),
            user: auth()->user(),
            perPage: request('perPage', 15)
        );
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(LinkData $data): JsonResource
    {
        $this->authorize('create', Link::class);

        $link = CreateLink::run($data);

        return LinkResource::make($link);
    }

    /**
     * Display the given link.
     */
    public function show(Link $link): JsonResource
    {
        $this->authorize('view', $link);

        return LinkResource::make($link);
    }

    /**
     * Update the given link in storage.
     */
    public function update(Link $link, LinkData $data): JsonResource
    {
        $this->authorize('update', $link);

        UpdateLink::run($link, $data);

        return LinkResource::make($link->refresh());
    }

    /**
     * Remove the given link from storage.
     */
    public function destroy(Link $link): Response
    {
        $this->authorize('delete', $link);

        DeleteLink::run($link);

        return response()->noContent();
    }
}
