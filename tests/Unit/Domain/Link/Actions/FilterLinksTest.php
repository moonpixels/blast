<?php

use App\Domain\Link\Actions\FilterLinks;

beforeEach(function () {
    $this->team = createTeam();

    createLink(
        attributes: ['team_id' => $this->team->id],
        states: ['count' => 20]
    );
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

it('filters links by a search term', function () {
    createLink(attributes: [
        'team_id' => $this->team->id,
        'alias' => 'testing',
    ]);

    $links = FilterLinks::run($this->team, 'testing');

    expect($links->count())->toBe(1)
        ->and($links->first()->alias)->toBe('testing');
});

it('returns an empty collection if no results are found', function () {
    $links = FilterLinks::run($this->team, 'link does not exist');

    expect($links->count())->toBe(0);
});
