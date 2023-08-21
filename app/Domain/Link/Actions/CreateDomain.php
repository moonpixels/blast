<?php

namespace App\Domain\Link\Actions;

use App\Domain\Link\Data\DomainData;
use App\Domain\Link\Models\Domain;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateDomain
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
