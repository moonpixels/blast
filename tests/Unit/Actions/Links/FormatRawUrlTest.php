<?php

use App\Actions\Links\FormatRawUrl;

it('removes whitespace from the URL', function () {
    $url = ' https://blst.to ';

    $formattedUrl = FormatRawUrl::run($url);

    expect($formattedUrl)->toBe('https://blst.to');
});

it('adds a protocol to the URL if one is not present', function () {
    $url = 'blst.to';

    $formattedUrl = FormatRawUrl::run($url);

    expect($formattedUrl)->toBe('https://blst.to');
});

it('removes www. from the URL', function () {
    $url = 'https://www.blst.to';

    $formattedUrl = FormatRawUrl::run($url);

    expect($formattedUrl)->toBe('https://blst.to');

    $url = 'www.blst.to';

    $formattedUrl = FormatRawUrl::run($url);

    expect($formattedUrl)->toBe('https://blst.to');
});
