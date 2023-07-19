<?php

use App\Rules\NotReservedAlias;

beforeEach(function () {
    $this->rule = new NotReservedAlias;
});

it('passes when the alias is not on the reserved list', function () {
    $validator = Validator::make([
        'alias' => 'my-alias',
    ], [
        'alias' => $this->rule,
    ]);

    expect($validator->passes())->toBeTrue();
});

it('fails when the alias is on the reserved list', function () {
    $validator = Validator::make([
        'alias' => 'admin',
    ], [
        'alias' => $this->rule,
    ]);

    expect($validator->passes())->toBeFalse();
});

it('fails when the alias is an app route', function () {
    $validator = Validator::make([
        'alias' => 'login',
    ], [
        'alias' => $this->rule,
    ]);

    expect($validator->passes())->toBeFalse();
});
