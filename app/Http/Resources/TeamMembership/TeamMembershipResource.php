<?php

namespace App\Http\Resources\TeamMembership;

use App\Http\Resources\Team\TeamResource;
use App\Http\Resources\User\UserResource;
use App\Models\TeamMembership;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TeamMembership */
class TeamMembershipResource extends JsonResource
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
            'team_id' => $this->team_id,
            'user_id' => $this->user_id,

            'team' => new TeamResource($this->whenLoaded('team')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
