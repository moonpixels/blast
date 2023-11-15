<?php

namespace Domain\Link\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckLinkAliasIsAllowedAction
{
    use AsAction;

    /**
     * Check if the alias is allowed.
     */
    public function handle(string $alias): bool
    {
        return $this->isAliasAllowed($alias);
    }

    /**
     * Check if the alias is allowed.
     */
    protected function isAliasAllowed(string $alias): bool
    {
        $blockList = $this->getRoutePaths()->merge($this->getReservedAliases())->unique();

        return ! $blockList->contains(function ($blockedAlias) use ($alias) {
            return Str::lower($blockedAlias) === Str::lower($alias);
        });
    }

    /**
     * Get the paths of all the routes in the application.
     */
    protected function getRoutePaths(): Collection
    {
        return collect(app('router')->getRoutes())->map(function ($route) {
            return Str::before($route->uri(), '/');
        });
    }

    /**
     * Get the reserved aliases from the config file.
     */
    protected function getReservedAliases(): Collection
    {
        return collect(config('blast.reserved_aliases'));
    }
}
