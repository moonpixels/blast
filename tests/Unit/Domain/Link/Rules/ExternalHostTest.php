<?php

use App\Domain\Link\Rules\ExternalHost;

beforeEach(function () {
    $this->rule = new ExternalHost;
});

it('passes when the host is valid', function () {
    $validator = Validator::make([
        'url' => 'https://example.com',
    ], [
        'url' => $this->rule,
    ]);

    expect($validator->passes())->toBeTrue();
});

it('fails when the host in invalid', function () {
    $validator = Validator::make([
        'url' => config('app.url'),
    ], [
        'url' => $this->rule,
    ]);

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->first('url'))->toBe('The url must be an external URL.');
});
