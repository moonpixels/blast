<?php

namespace App\Http\Resources;

use App\Domain\Link\Models\Link;
use App\Support\Concerns\Unwrappable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Link */
class LinkResource extends JsonResource
{
    use Unwrappable;

    /**
     * Transform the resource into an array.
     */
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
