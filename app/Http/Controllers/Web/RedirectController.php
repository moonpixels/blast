<?php

namespace App\Http\Controllers\Web;

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Actions\CreateVisit;
use App\Domain\Redirect\Data\RedirectData;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RedirectController extends Controller
{
    /**
     * Redirect the user to the destination URL.
     */
    public function show(Link $link): Response|RedirectResponse
    {
        if ($link->has_password) {
            return inertia('redirects/index', [
                'alias' => $link->alias,
                'status' => 'protected',
            ]);
        }

        if ($link->hasExpired()) {
            return inertia('redirects/index', [
                'alias' => $link->alias,
                'status' => 'expired',
            ]);
        }

        if ($link->hasReachedVisitLimit()) {
            return inertia('redirects/index', [
                'alias' => $link->alias,
                'status' => 'limited',
            ]);
        }

        return $this->handleRedirect($link);
    }

    /**
     * Authenticate the redirect request.
     */
    public function authenticate(Link $link, RedirectData $data): HttpResponse|RedirectResponse
    {
        if (! $link->has_password) {
            return redirect()->route('redirect', $link->alias);
        }

        if (! $link->passwordMatches($data->password)) {
            return back()->withErrors([
                'password' => __('The provided password is incorrect.'),
            ]);
        }

        return Inertia::location($this->handleRedirect($link));
    }

    /**
     * Handle the redirect.
     */
    protected function handleRedirect(Link $link): RedirectResponse
    {
        CreateVisit::run($link, request()->userAgent(), request()->header('referer'));

        return redirect()->away($link->destination_url, 301, [
            'cache-control' => 'no-cache, no-store',
        ]);
    }
}
