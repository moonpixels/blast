<?php

namespace App\Http\Controllers\Web\Links;

use App\Actions\Redirects\GetLinkForRedirectRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthenticatedRedirect\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthenticatedRedirectController extends Controller
{
    /**
     * Show the password input page for the link associated with the given alias.
     */
    public function create(Request $request, string $alias): Response|RedirectResponse
    {
        $link = GetLinkForRedirectRequest::run($alias);

        if (! $link->has_password) {
            return redirect()->route('redirect', $alias);
        }

        return inertia('Redirects/Authenticated/Create', [
            'alias' => $alias,
        ]);
    }

    /**
     * Attempt to authenticate the redirect request.
     */
    public function store(StoreRequest $request, string $alias): RedirectResponse|HttpResponse
    {
        $link = GetLinkForRedirectRequest::run($alias);

        if (! $link->has_password) {
            return redirect()->route('redirect', $alias);
        }

        if (! $link->passwordMatches($request->input('password'))) {
            return back()->withErrors([
                'password' => __('The provided password is incorrect.'),
            ]);
        }

        session()->flash("authenticated:{$alias}");

        return Inertia::location(route('redirect', $alias));
    }
}
