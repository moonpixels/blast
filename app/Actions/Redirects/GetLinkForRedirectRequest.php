<?php

namespace App\Actions\Redirects;

use App\Models\Link;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLinkForRedirectRequest
{
    use AsAction;

    /**
     * Get the link for the given redirect request.
     */
    public function handle(string $alias): Link
    {
        return Link::where('alias', $alias)->firstOrFail();
    }
}
