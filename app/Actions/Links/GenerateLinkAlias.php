<?php

namespace App\Actions\Links;

use App\Models\Link;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateLinkAlias
{
    use AsAction;

    /**
     * Generate a unique link alias.
     */
    public function handle(): string
    {
        $attempts = 0;
        $alias = '';
        $taken = true;

        while ($taken) {
            $alias = Str::random(7);
            $taken = Link::where('alias', $alias)->exists();
            $attempts++;
        }

        Log::info('Link alias generated.', [
            'alias' => $alias,
            'attempts' => $attempts,
        ]);

        return $alias;
    }
}
