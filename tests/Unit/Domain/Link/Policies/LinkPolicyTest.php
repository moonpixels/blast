<?php

use App\Domain\Link\Policies\LinkPolicy;

beforeEach(function () {
    $this->policy = new LinkPolicy();

    $this->user = createUser();

    $this->ownedLink = createLink(attributes: [
        'team_id' => getTeamForUser($this->user, 'Owned Team')->id,
    ]);

    $this->memberLink = createLink(attributes: [
        'team_id' => getTeamForUser($this->user, 'Member Team')->id,
    ]);

    $this->otherLink = createLink();
});

it('only lets team members view links', function () {
    expect($this->policy->view($this->user, $this->ownedLink))->toBeTrue()
        ->and($this->policy->view($this->user, $this->memberLink))->toBeTrue()
        ->and($this->policy->view($this->user, $this->otherLink))->toBeFalse();
});

it('only lets team members update links', function () {
    expect($this->policy->update($this->user, $this->ownedLink))->toBeTrue()
        ->and($this->policy->update($this->user, $this->memberLink))->toBeTrue()
        ->and($this->policy->update($this->user, $this->otherLink))->toBeFalse();
});

it('only lets team members delete links', function () {
    expect($this->policy->delete($this->user, $this->ownedLink))->toBeTrue()
        ->and($this->policy->delete($this->user, $this->memberLink))->toBeTrue()
        ->and($this->policy->delete($this->user, $this->otherLink))->toBeFalse();
});
