<?php

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->team = Team::factory()->for($this->user, 'owner')->create();

    $this->memberUser = User::factory()->create();
    $this->team->users()->attach($this->memberUser);

    $this->nonMemberUser = User::factory()->create();

    $this->policy = new TeamPolicy();
});

it('allows owners to view teams', function () {
    expect($this->policy->view($this->user, $this->team))->toBeTrue();
});

it('allows owners to update teams', function () {
    expect($this->policy->update($this->user, $this->team))->toBeTrue();
});

it('allows owners to delete standard teams', function () {
    expect($this->policy->delete($this->user, $this->team))->toBeTrue();
});

it('does not allow owners to delete personal teams', function () {
    expect($this->policy->delete($this->user, $this->user->personalTeam()))->toBeFalse();
});

it('allows owners to invite team members', function () {
    expect($this->policy->inviteMember($this->user, $this->team))->toBeTrue();
});

it('does not allow owners to invite team members to personal teams', function () {
    expect($this->policy->inviteMember($this->user, $this->user->personalTeam()))->toBeFalse();
});

it('does not allow non-owners to view teams', function () {
    expect($this->policy->view($this->memberUser, $this->team))->toBeFalse()
        ->and($this->policy->view($this->nonMemberUser, $this->team))->toBeFalse();
});

it('does not allow non-owners to update teams', function () {
    expect($this->policy->update($this->memberUser, $this->team))->toBeFalse()
        ->and($this->policy->update($this->nonMemberUser, $this->team))->toBeFalse();
});

it('does not allow non-owners to delete teams', function () {
    expect($this->policy->delete($this->memberUser, $this->team))->toBeFalse()
        ->and($this->policy->delete($this->nonMemberUser, $this->team))->toBeFalse();
});

it('does not allow non-owners to invite team members', function () {
    expect($this->policy->inviteMember($this->memberUser, $this->team))->toBeFalse()
        ->and($this->policy->inviteMember($this->nonMemberUser, $this->team))->toBeFalse();
});
