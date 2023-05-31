<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Team\TeamResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'current_team_id' => $this->current_team_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'initials' => $this->initials,
            'two_factor_enabled' => $this->two_factor_enabled,

            'owned_teams_count' => $this->whenCounted($this->ownedTeams),
            'teams_count' => $this->whenCounted($this->teams),

            'current_team' => new TeamResource($this->whenLoaded('currentTeam')),
            'owned_teams' => TeamResource::collection($this->whenLoaded('ownedTeams')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
        ];
    }
}
