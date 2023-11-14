<?php

namespace Domain\Link\Actions;

use Domain\Link\DTOs\DomainData;
use Domain\Link\Models\Domain;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateDomainAction
{
    use AsAction;

    /**
     * Create a new domain.
     */
    public function handle(DomainData $data): Domain
    {
        return Domain::firstOrCreate([
            'host' => $data->host,
        ]);
    }
}
