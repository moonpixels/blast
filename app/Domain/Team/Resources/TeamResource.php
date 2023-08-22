<?php

namespace App\Domain\Team\Resources;

use App\Domain\Link\Resources\LinkResource;
use App\Domain\Team\Models\Team;
use App\Domain\User\Resources\UserResource;
use App\Support\Concerns\Unwrappable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Team */
class TeamResource extends JsonResource
{
    use Unwrappable;

    /**
     * Transform the resource into an array.
     */
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
