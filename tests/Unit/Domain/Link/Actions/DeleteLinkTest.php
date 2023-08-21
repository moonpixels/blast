<?php

use App\Domain\Link\Actions\DeleteLink;

beforeEach(function () {
    $this->link = createLink();
});

it('deletes the link', function () {
    expect(DeleteLink::run($this->link))->toBeTrue()
        ->and($this->link)->toBeSoftDeleted();
});
