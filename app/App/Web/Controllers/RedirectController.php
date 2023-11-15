<?php

namespace App\Web\Controllers;

use App\Controller;
use App\Web\Requests\RedirectRequest;
use Domain\Link\Models\Link;
use Domain\Redirect\Actions\CreateVisitAction;
use Domain\Redirect\DTOs\VisitData;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RedirectController extends Controller
{
    /**
     * Redirect the user to the destination URL.
     */
    public function __invoke(RedirectRequest $request, Link $link): Response|SymfonyResponse
    {
        $status = match (true) {
            $link->has_password && ! $request->has('password') => 'protected',
            $link->hasExpired() => 'expired',
            $link->hasReachedVisitLimit() => 'limited',
            default => null,
        };

        if ($status) {
            return inertia('redirects/index', [
                'alias' => $link->alias,
                'status' => $status,
            ]);
        }

        CreateVisitAction::run($link, VisitData::from([
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
        ]));

        return Inertia::location(redirect()->away($link->destination_url, 301, [
            'cache-control' => 'no-cache, no-store',
        ]));
    }
}
