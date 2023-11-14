<?php

namespace App\Web\Controllers;

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
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LinkController extends Controller
{
    /**
     * Show a list of links.
     */
    public function index(LinkListRequest $request, LinkListQuery $linkQuery): Response
    {
        $this->authorize('viewAny', Link::class);

        $links = $linkQuery->paginate()->withQueryString();

        return inertia('links/index', [
            'filters' => [
                'search' => $request->validated('filter.search'),
            ],
            'shortenedLink' => Inertia::lazy(function () use ($request) {
                if ($request->session()->has('shortened_link')) {
                    return LinkResource::createWithoutWrapping(
                        $request->session()->get('shortened_link')
                    );
                }

                return null;
            }),
            'links' => LinkResource::collection($links),
        ]);
    }

    /**
     * Create a new link.
     */
    public function store(LinkCreateRequest $request): RedirectResponse
    {
        $this->authorize('create', Link::class);

        $link = CreateLinkAction::run($request->toDTO());

        return back()
            ->with('shortened_link', $link)
            ->with('success', [
                'title' => __('Link created'),
                'message' => __('The link has been created.'),
            ]);
    }

    /**
     * Update the given link.
     */
    public function update(LinkUpdateRequest $request, Link $link): RedirectResponse
    {
        $this->authorize('update', $link);

        UpdateLinkAction::run($link, $request->toDTO());

        return back()->with('success', [
            'title' => __('Link updated'),
            'message' => __('The link has been updated.'),
        ]);
    }

    /**
     * Delete the given link.
     */
    public function destroy(Link $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        DeleteLinkAction::run($link);

        return back()->with('success', [
            'title' => __('Link deleted'),
            'message' => __('The link has been deleted.'),
        ]);
    }
}
