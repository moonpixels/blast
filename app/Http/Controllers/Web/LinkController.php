<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LinkController extends Controller
{
    /**
     * Show a list of links.
     */
    public function index(): Response
    {
        return Inertia::render('Links/Index');
    }
}
