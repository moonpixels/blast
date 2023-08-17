<?php

use App\Domain\Link\Actions\DeleteLink;
use App\Domain\Link\Models\Link;

beforeEach(function () {
    $this->link = Link::factory()->create();
});

it('can soft delete a link', function () {
    expect(DeleteLink::run($this->link))->toBeTrue();

    $this->assertSoftDeleted('links', [
        'id' => $this->link->id,
    ]);
});
