<?php

namespace App\Http\Controllers\Web;

use App\Actions\Links\CreateLink;
use App\Actions\Links\FilterLinks;
use App\Exceptions\InvalidUrlException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\StoreRequest;
use App\Http\Resources\Link\LinkResource;
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
                'search' => $request->query('search'),
            ],
            'shortenedLink' => Inertia::lazy(function () use ($request) {
                if ($request->session()->has('shortened_link')) {
                    return LinkResource::createWithoutWrapping(
                        $request->session()->get('shortened_link')
                    );
                }

                return null;
            }),
            'links' => FilterLinks::run($request->user()->currentTeam),
        ]);
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            $link = CreateLink::run($request->validated());
        } catch (InvalidUrlException) {
            return back()->withErrors([
                'url' => __('The URL is invalid.'),
            ]);
        }

        return back()
            ->with('shortened_link', $link)
            ->with('success', [
                'title' => __('Link created'),
                'message' => __('The link has been created.'),
            ]);
    }
}
