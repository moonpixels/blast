<?php

namespace App\Support\Concerns;

use Illuminate\Support\Str;

trait HasUrlInput
{
    /**
     * Parse the given URL input.
     */
    public static function parseUrlInput(string $url): array
    {
        $host = Str::lower(parse_url($url, PHP_URL_HOST));

        $path = Str::after($url, $host) ?: null;

        return [
            'host' => $host,
            'path' => $path,
        ];
    }
}
