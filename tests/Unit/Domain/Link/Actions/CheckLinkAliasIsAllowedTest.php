<?php

use App\Domain\Link\Actions\CheckLinkAliasIsAllowed;

it('returns false when the alias is on the reserved list', function () {
    $this->assertFalse(CheckLinkAliasIsAllowed::run('admin'));
});

it('returns false when the alias is an app route', function () {
    $this->assertFalse(CheckLinkAliasIsAllowed::run('login'));
});

it('returns true when the alias is not reserved', function () {
    $this->assertTrue(CheckLinkAliasIsAllowed::run('my-alias'));
});
