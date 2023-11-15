<?php

use Domain\Link\Rules\NotReservedAliasRule;

beforeEach(function () {
    $this->rule = new NotReservedAliasRule;
});

it('passes when the alias is valid', function () {
    $validator = Validator::make([
        'alias' => 'testing',
    ], [
        'alias' => $this->rule,
    ]);

    expect($validator->passes())->toBeTrue();
});

it('fails when the alias in invalid', function () {
    $validator = Validator::make([
        'alias' => 'admin',
    ], [
        'alias' => $this->rule,
    ]);

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->first('alias'))->toBe('The alias has already been taken.');
});
