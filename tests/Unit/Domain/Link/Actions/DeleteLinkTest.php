<?php

use Domain\Link\Actions\DeleteLinkAction;

beforeEach(function () {
    $this->link = createLink();
});

it('deletes the link', function () {
    expect(DeleteLinkAction::run($this->link))->toBeTrue()
        ->and($this->link)->toBeSoftDeleted();
});
