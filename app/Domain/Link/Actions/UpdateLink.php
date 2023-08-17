<?php

namespace App\Domain\Link\Actions;

use App\Domain\Link\Data\LinkData;
use App\Domain\Link\Models\Domain;
use App\Domain\Link\Models\Link;
use App\Support\Concerns\HasUrlInput;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class UpdateLink
{
    use AsAction, HasUrlInput;

    /**
     * Update the given link.
     */
    public function handle(Link $link, LinkData $data): bool
    {
        return DB::transaction(function () use ($link, $data) {
            $link->fill($data->except('destinationUrl')->toArray());

            if (! $data->destinationUrl instanceof Optional) {
                $url = $this->parseUrlInput($data->destinationUrl);

                $domain = Domain::firstOrCreate([
                    'host' => $url['host'],
                ]);

                $link->domain_id = $domain->id;
                $link->destination_path = $url['path'];
            }

            return $link->save();
        });
    }
}
