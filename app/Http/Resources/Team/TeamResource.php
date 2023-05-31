<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\TeamInvitation\TeamInvitationResource;
use App\Http\Resources\User\UserResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Team */
class TeamResource extends JsonResource
{
    /**
     * Instantiate the resource.
     */
    public function __construct(mixed $resource, protected bool $withoutWrapping = false)
    {
        parent::__construct($resource);

        if ($this->withoutWrapping) {
            self::withoutWrapping();
        }
    }

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

            'pending_invitations_count' => $this->whenCounted($this->pendingInvitations),
            'users_count' => $this->whenCounted($this->users),

            'owner' => new UserResource($this->whenLoaded('owner')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'pending_invitations' => TeamInvitationResource::collection($this->whenLoaded('pendingInvitations')),
        ];
    }
}
