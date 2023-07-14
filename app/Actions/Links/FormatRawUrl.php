<?php

namespace App\Actions\Links;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class FormatRawUrl
{
    use AsAction;

    /**
     * Format a raw URL.
     */
    public function handle(string $url): string
    {
        // Remove any whitespace from the URL.
        $url = trim($url);

        // If the URL doesn't start with a protocol, add one.
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            $url = "https://{$url}";
        }

        // Remove www. from the URL.
        $url = Str::remove('www.', $url, false);

        return $url;
    }
}
