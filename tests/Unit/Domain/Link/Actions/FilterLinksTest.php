<?php

use App\Domain\Link\Actions\FilterLinks;
use App\Domain\Link\Models\Link;
use App\Domain\Team\Models\Team;

beforeEach(function () {
    $this->team = Team::factory()->create();

    Link::factory(20)->for($this->team)->create();
});

it('returns all results if a search term is not sent', function () {
    $links = FilterLinks::run($this->team);

    expect($links->count())->toBe(15);
});

it('returns filtered results if a search term is sent', function () {
    Link::factory()->for($this->team)->create(['alias' => 'myAlias']);

    $links = FilterLinks::run($this->team, 'myAlias');

    expect($links->count())->toBe(1);
});

it('returns an empty collection if no results are found', function () {
    $links = FilterLinks::run($this->team, 'link does not exist');

    expect($links->count())->toBe(0);
});

it('returns a paginated collection', function () {
    $links = FilterLinks::run($this->team);

    expect($links->currentPage())->toBe(1)
        ->and($links->perPage())->toBe(15)
        ->and($links->hasPages())->toBeTrue()
        ->and($links->hasMorePages())->toBeTrue()
        ->and($links->lastPage())->toBe(2)
        ->and($links->previousPageUrl())->toBeNull();
});
