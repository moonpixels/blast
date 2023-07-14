<?php

use App\Actions\Links\FilterLinks;
use App\Models\Link;
use App\Models\Team;

beforeEach(function () {
    $this->team = Team::factory()->create();

    Link::factory(15)->for($this->team)->create();
});

it('returns all results if a search term is not sent', function () {
    $links = FilterLinks::run($this->team);

    expect($links->count())->toBe(10);
});

it('returns a paginated collection', function () {
    $links = FilterLinks::run($this->team);

    expect($links->currentPage())->toBe(1)
        ->and($links->perPage())->toBe(10)
        ->and($links->hasPages())->toBeTrue()
        ->and($links->hasMorePages())->toBeTrue()
        ->and($links->lastPage())->toBe(2)
        ->and($links->previousPageUrl())->toBeNull();
});
