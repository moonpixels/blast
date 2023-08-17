<?php

use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamMembership;
use App\Domain\Team\Models\User;

beforeEach(function () {
    $this->membership = TeamMembership::factory()->create();
});

it('returns the correct indexable array', function () {
    expect($this->membership->toSearchableArray())->toHaveKeys([
        'id',
        'team_id',
        'user_id',
        'user_name',
        'user_email',
        'created_at',
        'updated_at',
    ]);
});

it('belongs to a team', function () {
    expect($this->membership->team)->toBeInstanceOf(Team::class);
});

it('belongs to a user', function () {
    expect($this->membership->user)->toBeInstanceOf(User::class);
});
