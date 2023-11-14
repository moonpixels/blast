<?php

namespace App\Api\Controllers\V1;

use App\Api\QueryBuilders\LinkListQuery;
use App\Api\Requests\LinkCreateRequest;
use App\Api\Requests\LinkListRequest;
use App\Api\Requests\LinkUpdateRequest;
use App\Api\Resources\LinkResource;
use App\Controller;
use Domain\Link\Actions\CreateLinkAction;
use Domain\Link\Actions\DeleteLinkAction;
use Domain\Link\Actions\UpdateLinkAction;
use Domain\Link\Models\Link;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class LinkController extends Controller
{
    /**
     * Retrieve a list of links.
     */
    public function index(LinkListRequest $request, LinkListQuery $linkQuery): ResourceCollection
    {
        $this->authorize('viewAny', Link::class);

        $links = $linkQuery->paginate()->withQueryString();

        return LinkResource::collection($links);
    }

    /**
     * Create a new link.
     */
    public function store(LinkCreateRequest $request): JsonResource
    {
        $this->authorize('create', Link::class);

        $link = CreateLinkAction::run($request->toDTO());

        return LinkResource::make($link);
    }

    /**
     * Retrieve the given link.
     */
    public function show(Link $link): JsonResource
    {
        $this->authorize('view', $link);

        return LinkResource::make($link);
    }

    /**
     * Update the given link.
     */
    public function update(LinkUpdateRequest $request, Link $link): JsonResource
    {
        $this->authorize('update', $link);

        UpdateLinkAction::run($link, $request->toDTO());

        return LinkResource::make($link->refresh());
    }

    /**
     * Delete the given link.
     */
    public function destroy(Link $link): Response
    {
        $this->authorize('delete', $link);

        DeleteLinkAction::run($link);

        return response()->noContent();
    }
}
