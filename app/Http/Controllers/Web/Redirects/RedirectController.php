<?php

namespace App\Http\Controllers\Web\Redirects;

use App\Actions\Redirects\GetLinkForRedirectRequest;
use App\Actions\Visits\CreateVisitForLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * Redirect the user to the destination URL for the given alias.
     */
    public function create(Request $request, string $alias): RedirectResponse
    {
        $link = GetLinkForRedirectRequest::run($alias);

        if ($link->has_password && ! session()->pull("authenticated:{$alias}", false)) {
            return redirect()->route('authenticated-redirect', $alias);
        }

        if ($link->hasExpired()) {
            return redirect()->route('expired-redirect');
        }

        if ($link->hasReachedVisitLimit()) {
            return redirect()->route('reached-visit-limit-redirect');
        }

        CreateVisitForLink::run($link, $request->userAgent(), $request->header('referer'));

        return redirect()->away($link->destination_url, 301, [
            'cache-control' => 'no-cache, no-store',
        ]);
    }
}
