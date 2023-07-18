<?php

use App\Actions\Links\GenerateLinkAlias;
use Illuminate\Support\Facades\Log;

it('can generate a unique alias', function () {
    $alias = GenerateLinkAlias::run();

    expect($alias)->not->toBeNull();
});

it('can generate a unique alias in a reasonable amount of time', function () {
    $start = microtime(true);

    GenerateLinkAlias::run();

    $end = microtime(true);

    expect($end - $start)->toBeLessThan(0.1);
});

it('logs the number of attempts it took to generate a unique alias', function () {
    Log::shouldReceive('info')
        ->once()
        ->withArgs(function ($message, $context) {
            return $message === 'Link alias generated.' && $context['attempts'] === 1;
        });

    GenerateLinkAlias::run();
});
