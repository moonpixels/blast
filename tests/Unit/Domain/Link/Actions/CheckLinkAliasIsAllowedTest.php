<?php

use App\Domain\Link\Actions\CheckLinkAliasIsAllowed;

it('returns false when the alias is on the reserved list', function () {
    expect(CheckLinkAliasIsAllowed::run('admin'))->toBeFalse();
});

it('returns false when the alias is an app route', function () {
    expect(CheckLinkAliasIsAllowed::run('login'))->toBeFalse();
});

it('returns true when the alias is not reserved', function () {
    expect(CheckLinkAliasIsAllowed::run('abc123'))->toBeTrue();
});
