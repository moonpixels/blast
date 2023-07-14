<?php

namespace App\Actions\Links;

use App\Exceptions\InvalidUrlException;
use App\Models\Domain;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateLink
{
    use AsAction;

    /**
     * Create a new link.
     *
     * @throws InvalidUrlException
     */
    public function handle(array $data): Link
    {
        $host = Str::lower(parse_url($data['url'], PHP_URL_HOST));

        if (! $host) {
            throw InvalidUrlException::invalidHost();
        }

        $path = Str::after($data['url'], $host);

        $alias = $data['alias'] ?? GenerateLinkAlias::run();

        return DB::transaction(function () use (&$data, $host, $path, $alias) {
            $domain = Domain::firstOrCreate([
                'host' => $host,
            ]);

            return Link::create([
                'team_id' => $data['team_id'],
                'domain_id' => $domain->id,
                'destination_path' => $path,
                'alias' => $alias,
            ]);
        });
    }
}
