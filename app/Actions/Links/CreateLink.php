<?php

namespace App\Actions\Links;

use App\Concerns\HasUrlInput;
use App\Data\LinkData;
use App\Exceptions\InvalidUrlException;
use App\Models\Domain;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateLink
{
    use AsAction, HasUrlInput;

    /**
     * Create a new link.
     *
     * @throws InvalidUrlException
     */
    public function handle(LinkData $data): Link|RedirectResponse
    {
        $url = $this->parseUrlInput($data->destinationUrl);

        $alias = $data->alias ?? GenerateLinkAlias::run();

        return DB::transaction(function () use (&$data, $url, $alias) {
            $domain = Domain::firstOrCreate([
                'host' => $url['host'],
            ]);

            return Link::create([
                'team_id' => $data->teamId,
                'domain_id' => $domain->id,
                'destination_path' => $url['path'],
                'alias' => $alias,
            ]);
        });
    }
}
