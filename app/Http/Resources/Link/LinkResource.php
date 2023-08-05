<?php

namespace App\Http\Resources\Link;

use App\Concerns\Unwrappable;
use App\Models\Link;
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
            'team_id' => $this->team_id,
            'alias' => $this->alias,
            'has_password' => $this->has_password,
            'visit_limit' => $this->visit_limit,
            'destination_url' => $this->destination_url,
            'short_url' => $this->short_url,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
