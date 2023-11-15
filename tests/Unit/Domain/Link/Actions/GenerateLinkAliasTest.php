<?php

use Domain\Link\Actions\CheckLinkAliasIsAllowedAction;
use Domain\Link\Actions\GenerateLinkAliasAction;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;

it('generates an alias', function () {
    $alias = GenerateLinkAliasAction::run();

    expect($alias)->toBeString()
        ->and(strlen($alias))->toBe(7);
});

it('logs a warning if it took multiple attempts to generate an alias', function () {
    Log::shouldReceive('warning')
        ->once()
        ->withArgs(function ($message, $context) {
            return $message === 'Link alias took multiple attempts to generate.'
                && is_string($context['alias'])
                && $context['attempts'] === 2;
        });

    $this->mock(CheckLinkAliasIsAllowedAction::class, function (MockInterface $mock) {
        $mock->shouldReceive('handle')
            ->andReturnFalse()
            ->once()
            ->ordered();

        $mock->shouldReceive('handle')
            ->andReturnTrue()
            ->once()
            ->ordered();
    });

    GenerateLinkAliasAction::run();
});
