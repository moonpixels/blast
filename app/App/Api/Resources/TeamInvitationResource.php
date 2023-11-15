<?php

namespace App\Api\Resources;

use Domain\Team\Models\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TeamInvitation */
class TeamInvitationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'team' => new TeamResource($this->whenLoaded('team')),
        ];
    }
}
