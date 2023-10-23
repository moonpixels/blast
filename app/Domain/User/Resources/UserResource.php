<?php

namespace App\Domain\User\Resources;

use App\Domain\Team\Resources\TeamResource;
use App\Domain\User\Models\User;
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
            'subscribed' => $this->when(auth()->user()->is($this->resource), fn () => $this->subscribed()),
            'current_team' => new TeamResource($this->whenLoaded('currentTeam')),
            'teams' => $this->when(
                $this->relationLoaded('teams') && $this->relationLoaded('ownedTeams'),
                fn () => TeamResource::collection($this->allTeams())
            ),
        ];
    }
}
