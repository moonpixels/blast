<?php

namespace App\Http\Controllers\Web\Redirects;

use App\Domain\Link\Models\Link;
use App\Domain\Redirect\Data\RedirectData;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthenticatedRedirectController extends Controller
{
    /**
     * Show the password input page for the link associated with the given alias.
     */
    public function create(Link $link): Response|RedirectResponse
    {
        if (! $link->has_password) {
            return redirect()->route('redirect', $link->alias);
        }

        return inertia('Redirects/Authenticated/Create', [
            'alias' => $link->alias,
        ]);
    }

    /**
     * Attempt to authenticate the redirect request.
     */
    public function store(Link $link, RedirectData $data): RedirectResponse|HttpResponse
    {
        if (! $link->has_password) {
            return redirect()->route('redirect', $link->alias);
        }

        if (! $link->passwordMatches($data->password)) {
            return back()->withErrors([
                'password' => __('The provided password is incorrect.'),
            ]);
        }

        session()->flash("authenticated:{$link->alias}");

        return Inertia::location(route('redirect', $link->alias));
    }
}
