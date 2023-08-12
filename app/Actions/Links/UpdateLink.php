<?php

namespace App\Actions\Links;

use App\Concerns\HasUrlInput;
use App\Data\LinkData;
use App\Exceptions\InvalidUrlException;
use App\Models\Domain;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class UpdateLink
{
    use AsAction, HasUrlInput;

    /**
     * Update the given link.
     *
     * @throws InvalidUrlException
     */
    public function handle(Link $link, LinkData $data): bool
    {
        $url = $this->parseUrlInput($data->destinationUrl);

        $alias = $data->alias ?? GenerateLinkAlias::run();

        return DB::transaction(function () use ($link, &$data, $url, $alias) {
            $domain = Domain::firstOrCreate([
                'host' => $url['host'],
            ]);

            if (! $data->password instanceof Optional) {
                $link->password = $data->password ? Hash::make($data->password) : null;
            }

            $link->fill([
                'team_id' => $data->teamId,
                'domain_id' => $domain->id,
                'destination_path' => $url['path'],
                'alias' => $alias,
                'visit_limit' => $data->visitLimit ?? null,
                'expires_at' => $data->expiresAt?->utc(),
            ]);

            return $link->save();
        });
    }
}
