<?php

namespace App\Api\Resources;

use Domain\Team\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Concerns\Unwrappable;

/** @mixin Team */
class TeamResource extends JsonResource
{
    use Unwrappable;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'personal_team' => $this->personal_team,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'owner' => new UserResource($this->whenLoaded('owner')),

            'members' => UserResource::collection($this->whenLoaded('users')),
            'members_count' => $this->whenCounted('users'),

            'invitations' => TeamInvitationResource::collection($this->whenLoaded('invitations')),
            'invitations_count' => $this->whenCounted('invitations'),

            'links' => LinkResource::collection($this->whenLoaded('links')),
            'links_count' => $this->whenCounted('links'),
        ];
    }
}
