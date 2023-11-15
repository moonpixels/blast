<?php

use Domain\Link\Actions\CheckLinkAliasIsAllowedAction;

it('returns false when the alias is on the reserved list', function () {
    expect(CheckLinkAliasIsAllowedAction::run('admin'))->toBeFalse();
});

it('returns false when the alias is an app route', function () {
    expect(CheckLinkAliasIsAllowedAction::run('login'))->toBeFalse();
});

it('returns true when the alias is not reserved', function () {
    expect(CheckLinkAliasIsAllowedAction::run('abc123'))->toBeTrue();
});
