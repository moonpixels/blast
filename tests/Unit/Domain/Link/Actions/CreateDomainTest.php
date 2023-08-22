<?php

use App\Domain\Link\Actions\CreateDomain;
use App\Domain\Link\Data\DomainData;

beforeEach(function () {
    $this->domainData = DomainData::from([
        'host' => 'example.com',
    ]);
});

it('creates a new domain', function () {
    $domain = CreateDomain::run($this->domainData);

    expect($domain)->toExistInDatabase()
        ->and($domain->host)->toBe('example.com');
});

it('returns an existing domain if it exists', function () {
    $domain = createDomain(attributes: ['host' => 'example.com']);

    expect($domain->is(CreateDomain::run($this->domainData)))->toBeTrue();
});
