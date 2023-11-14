<?php

use Domain\Link\Actions\CreateDomainAction;
use Domain\Link\DTOs\DomainData;

beforeEach(function () {
    $this->domainData = DomainData::from([
        'host' => 'example.com',
    ]);
});

it('creates a new domain', function () {
    $domain = CreateDomainAction::run($this->domainData);

    expect($domain)->toExistInDatabase()
        ->and($domain->host)->toBe('example.com');
});

it('returns an existing domain if it exists', function () {
    $domain = createDomain(attributes: ['host' => 'example.com']);

    expect($domain->is(CreateDomainAction::run($this->domainData)))->toBeTrue();
});
