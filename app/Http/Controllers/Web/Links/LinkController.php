<?php

namespace App\Http\Controllers\Web\Links;

use App\Domain\Link\Actions\CreateLink;
use App\Domain\Link\Actions\DeleteLink;
use App\Domain\Link\Actions\FilterLinks;
use App\Domain\Link\Actions\UpdateLink;
use App\Domain\Link\Data\LinkData;
use App\Domain\Link\Models\Link;
use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LinkController extends Controller
{
    /**
     * Show a list of links.
     */
    public function index(): Response
    {
        return Inertia::render('Links/Index', [
            'filters' => [
                'query' => request()->query('query'),
            ],
            'shortenedLink' => Inertia::lazy(function () {
                if (request()->session()->has('shortened_link')) {
                    return LinkResource::createWithoutWrapping(
                        request()->session()->get('shortened_link')
                    );
                }

                return null;
            }),
            'links' => FilterLinks::run(request()->user()->currentTeam, request()->query('query')),
        ]);
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(LinkData $data): RedirectResponse
    {
        $link = CreateLink::run($data);

        return back()
            ->with('shortened_link', $link)
            ->with('success', [
                'title' => __('Link created'),
                'message' => __('The link has been created.'),
            ]);
    }

    /**
     * Update the specified link in storage.
     */
    public function update(Link $link, LinkData $data): RedirectResponse
    {
        $this->authorize('update', $link);

        UpdateLink::run($link, $data);

        return back()->with('success', [
            'title' => __('Link updated'),
            'message' => __('The link has been updated.'),
        ]);
    }

    /**
     * Remove the specified link from storage.
     */
    public function destroy(Link $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        DeleteLink::run($link);

        return back()->with('success', [
            'title' => __('Link deleted'),
            'message' => __('The link has been deleted.'),
        ]);
    }
}
