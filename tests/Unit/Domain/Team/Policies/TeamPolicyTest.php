<?php

use App\Domain\Team\Policies\TeamPolicy;

beforeEach(function () {
    $this->policy = new TeamPolicy();

    $this->user = createUser();

    $this->ownedTeam = getTeamForUser($this->user, 'Owned Team');
    $this->memberTeam = getTeamForUser($this->user, 'Member Team');
    $this->otherTeam = createTeam();
});

it('only lets team members view teams', function () {
    expect($this->policy->view($this->user, $this->ownedTeam))->toBeTrue()
        ->and($this->policy->view($this->user, $this->memberTeam))->toBeTrue()
        ->and($this->policy->view($this->user, $this->otherTeam))->toBeFalse();
});

it('only lets owners update teams', function () {
    expect($this->policy->update($this->user, $this->ownedTeam))->toBeTrue()
        ->and($this->policy->update($this->user, $this->memberTeam))->toBeFalse()
        ->and($this->policy->update($this->user, $this->otherTeam))->toBeFalse();
});

it('only lets owners delete non-personal teams', function () {
    expect($this->policy->delete($this->user, $this->ownedTeam))->toBeTrue()
        ->and($this->policy->delete($this->user, $this->user->personalTeam()))->toBeFalse()
        ->and($this->policy->delete($this->user, $this->memberTeam))->toBeFalse()
        ->and($this->policy->delete($this->user, $this->otherTeam))->toBeFalse();
});

it('only lets owners attach team members for non-personal teams', function () {
    expect($this->policy->attachAnyMember($this->user, $this->ownedTeam))->toBeTrue()
        ->and($this->policy->attachAnyMember($this->user, $this->user->personalTeam()))->toBeFalse()
        ->and($this->policy->attachAnyMember($this->user, $this->memberTeam))->toBeFalse()
        ->and($this->policy->attachAnyMember($this->user, $this->otherTeam))->toBeFalse();
});

it('only lets owners or member detach team members', function () {
    $this->ownedTeam->members()->attach($this->memberTeam->owner);
    $this->memberTeam->members()->attach($this->otherTeam->owner);
    $this->otherTeam->members()->attach($this->memberTeam->owner);

    expect($this->policy->detachMember($this->user, $this->ownedTeam, $this->memberTeam->owner))->toBeTrue()
        ->and($this->policy->detachMember($this->user, $this->memberTeam, $this->user))->toBeTrue()
        ->and($this->policy->detachMember($this->user, $this->memberTeam, $this->otherTeam->owner))->toBeFalse()
        ->and($this->policy->detachMember($this->user, $this->otherTeam, $this->memberTeam->owner))->toBeFalse();
});
