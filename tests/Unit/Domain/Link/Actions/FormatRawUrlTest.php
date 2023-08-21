<?php

use App\Domain\Link\Actions\FormatRawUrl;

it('leaves the URL alone if it is valid', function () {
    $url = 'https://example.com';

    $formattedUrl = FormatRawUrl::run($url);

    expect($formattedUrl)->toBe('https://example.com');
});

it('removes whitespace from the URL', function () {
    $url = ' https://example.com ';

    $formattedUrl = FormatRawUrl::run($url);

    expect($formattedUrl)->toBe('https://example.com');
});

it('adds a protocol to the URL if one is not present', function () {
    $url = 'example.com';

    $formattedUrl = FormatRawUrl::run($url);

    expect($formattedUrl)->toBe('https://example.com');
});
