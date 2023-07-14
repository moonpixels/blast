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
            'destination_url' => $this->destination_url,
            'short_url' => $this->short_url,
        ];
    }
}
