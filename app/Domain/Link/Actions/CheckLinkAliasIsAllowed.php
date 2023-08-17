<?php

namespace App\Domain\Link\Actions;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckLinkAliasIsAllowed
{
    use AsAction;

    /**
     * Check that the given alias is allowed.
     */
    public function handle(string $alias): bool
    {
        $reservedAliases = collect(app('router')->getRoutes())->map(function ($route) {
            return Str::before($route->uri(), '/');
        })->merge(config('blast.reserved_aliases'))->unique();

        return ! $reservedAliases->contains(function ($reservedAlias) use ($alias) {
            return Str::lower($reservedAlias) === Str::lower($alias);
        });
    }
}
