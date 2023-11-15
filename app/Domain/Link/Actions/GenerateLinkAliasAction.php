<?php

namespace Domain\Link\Actions;

use Domain\Link\Models\Link;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateLinkAliasAction
{
    use AsAction;

    /**
     * Generate a unique link alias.
     */
    public function handle(): string
    {
        $attempts = 0;
        $alias = '';
        $valid = false;

        while (! $valid) {
            $alias = Str::random(7);
            $valid = Link::where('alias',
                $alias)->withTrashed()->doesntExist() && CheckLinkAliasIsAllowedAction::run($alias);
            $attempts++;
        }

        if ($attempts > 1) {
            Log::warning('Link alias took multiple attempts to generate.', [
                'alias' => $alias,
                'attempts' => $attempts,
            ]);
        }

        return $alias;
    }
}
