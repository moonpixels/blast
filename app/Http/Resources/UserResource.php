<?php

namespace App\Http\Resources;

use App\Domain\Team\Models\User;
use App\Support\Concerns\Unwrappable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'initials' => $this->initials,
            'two_factor_enabled' => $this->when(auth()->user()->is($this->resource), $this->two_factor_enabled),
            'current_team' => new TeamResource($this->whenLoaded('currentTeam')),
            'teams' => $this->when(
                $this->relationLoaded('teams') && $this->relationLoaded('ownedTeams'),
                fn () => TeamResource::collection($this->allTeams())
            ),
        ];
    }
}
