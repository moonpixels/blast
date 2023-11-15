<?php

namespace App\Api\Resources;

use Domain\Link\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Concerns\Unwrappable;

/** @mixin Link */
class LinkResource extends JsonResource
{
    use Unwrappable;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'short_url' => $this->short_url,
            'destination_url' => $this->destination_url,
            'alias' => $this->alias,
            'has_password' => $this->has_password,
            'visit_limit' => $this->visit_limit,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'team' => new TeamResource($this->whenLoaded('team')),
        ];
    }
}
