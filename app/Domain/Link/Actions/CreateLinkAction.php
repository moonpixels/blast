<?php

namespace Domain\Link\Actions;

use Domain\Link\DTOs\LinkData;
use Domain\Link\Models\Link;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateLinkAction
{
    use AsAction;

    /**
     * Create a new link.
     */
    public function handle(LinkData $data): Link
    {
        return DB::transaction(function () use ($data) {
            $domain = CreateDomainAction::run($data->domain);

            return Link::create([
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
