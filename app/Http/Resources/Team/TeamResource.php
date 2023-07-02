<?php

namespace App\Http\Resources\Team;

use App\Concerns\Unwrappable;
use App\Http\Resources\TeamInvitation\TeamInvitationResource;
use App\Http\Resources\User\UserResource;
use App\Models\Team;
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
            'owner_id' => $this->owner_id,
            'name' => $this->name,
            'personal_team' => $this->personal_team,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'members' => UserResource::collection($this->whenLoaded('users')),
            'members_count' => $this->whenCounted('users'),

            'invitations' => TeamInvitationResource::collection($this->whenLoaded('invitations')),
            'invitations_count' => $this->whenCounted('invitations'),
        ];
    }
}
