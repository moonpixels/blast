<?php

namespace App\Http\Controllers\Web\Links;

use App\Actions\Links\CreateLink;
use App\Actions\Links\DeleteLink;
use App\Actions\Links\FilterLinks;
use App\Actions\Links\UpdateLink;
use App\Data\LinkData;
use App\Exceptions\InvalidUrlException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\StoreRequest;
use App\Http\Requests\Link\UpdateRequest;
use App\Http\Resources\Link\LinkResource;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LinkController extends Controller
{
    /**
     * Show a list of links.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Links/Index', [
            'filters' => [
                'query' => $request->query('query'),
            ],
            'shortenedLink' => Inertia::lazy(function () use ($request) {
                if ($request->session()->has('shortened_link')) {
                    return LinkResource::createWithoutWrapping(
                        $request->session()->get('shortened_link')
                    );
                }

                return null;
            }),
            'links' => FilterLinks::run($request->user()->currentTeam, $request->query('query')),
        ]);
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            $link = CreateLink::run(LinkData::from($request->validated()));
        } catch (InvalidUrlException) {
            return back()->withErrors([
                'destination_url' => __('The URL is invalid.'),
            ]);
        }

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
    public function update(UpdateRequest $request, Link $link): RedirectResponse
    {
        $this->authorize('update', $link);

        try {
            UpdateLink::run($link, LinkData::from($request->validated()));
        } catch (InvalidUrlException) {
            return back()->withErrors([
                'destination_url' => __('The URL is invalid.'),
            ]);
        }

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
