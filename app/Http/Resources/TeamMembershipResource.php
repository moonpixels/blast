<?php

namespace App\Http\Resources;

use App\Domain\Team\Models\TeamMembership;
use App\Support\Concerns\Unwrappable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TeamMembership */
class TeamMembershipResource extends JsonResource
{
    use Unwrappable;

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'team' => new TeamResource($this->whenLoaded('team')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
