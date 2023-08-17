<?php

namespace App\Domain\Link\Actions;

use App\Domain\Link\Data\LinkData;
use App\Domain\Link\Models\Domain;
use App\Domain\Link\Models\Link;
use App\Support\Concerns\HasUrlInput;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateLink
{
    use AsAction, HasUrlInput;

    /**
     * Create a new link.
     */
    public function handle(LinkData $data): Link
    {
        return DB::transaction(function () use ($data) {
            $url = $this->parseUrlInput($data->destinationUrl);

            $domain = Domain::firstOrCreate([
                'host' => $url['host'],
            ]);

            $link = new Link($data->except('destinationUrl')->toArray());

            $link->domain_id = $domain->id;
            $link->destination_path = $url['path'];

            if (! $link->alias) {
                $link->alias = GenerateLinkAlias::run();
            }

            $link->save();

            return $link;
        });
    }
}
