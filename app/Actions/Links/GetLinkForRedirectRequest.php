<?php

namespace App\Actions\Links;

use App\Models\Link;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLinkForRedirectRequest
{
    use AsAction;

    /**
     * Get the link for the given redirect request.
     */
    public function handle(string $alias): Link
    {
        return Cache::remember("links:{$alias}", 3600, function () use ($alias) {
            return Link::where('alias', $alias)->firstOrFail();
        });
    }
}
