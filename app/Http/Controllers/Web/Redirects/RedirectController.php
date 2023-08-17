<?php

namespace App\Http\Controllers\Web\Redirects;

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Actions\CreateVisit;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    /**
     * Redirect the user to the destination URL for the given alias.
     */
    public function create(Link $link): RedirectResponse
    {
        if ($link->has_password && ! session()->pull("authenticated:{$link->alias}", false)) {
            return redirect()->route('authenticated-redirect', $link->alias);
        }

        if ($link->hasExpired()) {
            return redirect()->route('expired-redirect');
        }

        if ($link->hasReachedVisitLimit()) {
            return redirect()->route('reached-visit-limit-redirect');
        }

        CreateVisit::run($link, request()->userAgent(), request()->header('referer'));

        return redirect()->away($link->destination_url, 301, [
            'cache-control' => 'no-cache, no-store',
        ]);
    }
}
