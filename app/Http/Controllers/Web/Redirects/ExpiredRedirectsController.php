<?php

namespace App\Http\Controllers\Web\Redirects;

use App\Http\Controllers\Controller;
use Inertia\Response;

class ExpiredRedirectsController extends Controller
{
    /**
     * Show the expired redirect page.
     */
    public function show(): Response
    {
        return inertia('Redirects/Expired/Show');
    }
}
