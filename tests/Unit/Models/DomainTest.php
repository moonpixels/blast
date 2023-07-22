<?php

use App\Models\Domain;
use App\Models\Link;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->domain = Domain::factory()->create();
});

it('has many links', function () {
    Link::factory(5)->for($this->domain)->create();

    $links = $this->domain->links;

    expect($links)->toHaveCount(5)
        ->and($links)->toBeInstanceOf(Collection::class)
        ->and($links)->each->toBeInstanceOf(Link::class);
});
