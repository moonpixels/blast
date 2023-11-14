<?php

use Domain\Link\Models\Link;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->domain = createDomain();
});

it('has many links', function () {
    createLink(
        attributes: ['domain_id' => $this->domain->id],
        states: ['count' => 5]
    );

    $links = $this->domain->links;

    expect($links)->toBeInstanceOf(Collection::class)
        ->and($links)->toHaveCount(5)
        ->and($links)->each->toBeInstanceOf(Link::class);
});
