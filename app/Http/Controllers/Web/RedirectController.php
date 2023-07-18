<?php

namespace App\Http\Controllers\Web;

use App\Actions\Links\GetLinkForRedirectRequest;
use App\Actions\Visits\CreateVisitForLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * Redirect the user to the destination URL for the given alias.
     */
    public function show(Request $request, string $alias): RedirectResponse
    {
        $link = GetLinkForRedirectRequest::run($alias);

        CreateVisitForLink::run($link, $request->userAgent(), $request->header('referer'));

        return redirect()->away($link->destination_url, 301, [
            'cache-control' => 'no-cache, no-store',
        ]);
    }
}
