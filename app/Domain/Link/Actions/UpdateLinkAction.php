<?php

namespace Domain\Link\Actions;

use Domain\Link\DTOs\LinkData;
use Domain\Link\Models\Link;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateLinkAction
{
    use AsAction;

    /**
     * Update a link.
     */
    public function handle(Link $link, LinkData $data): bool
    {
        return DB::transaction(function () use ($link, $data) {
            $domain = CreateDomainAction::run($data->domain);

            return $link->update([
                'team_id' => $data->team_id,
                'domain_id' => $domain->id,
                'destination_path' => $data->destination_path,
                'alias' => $data->alias ?? GenerateLinkAliasAction::run(),
                'password' => $data->password,
                'visit_limit' => $data->visit_limit,
                'expires_at' => $data->expires_at,
            ]);
        });
    }
}
