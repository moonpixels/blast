<?php

namespace Domain\Link\Support\Helpers;

use Illuminate\Support\Str;

class Url
{
    /**
     * Parse a URL into its host and path.
     */
    public static function parseUrl(string $url): array
    {
        $host = parse_url($url, PHP_URL_HOST);

        $path = Str::after($url, $host) ?: null;

        return [
            'host' => Str::lower($host),
            'path' => $path,
        ];
    }

    /**
     * Format a user-provided URL into a consistent format.
     */
    public static function formatFromInput(string $url): string
    {
        $url = trim($url);

        if (! Str::startsWith($url, ['http://', 'https://'])) {
            $url = "https://{$url}";
        }

        return $url;
    }
}
