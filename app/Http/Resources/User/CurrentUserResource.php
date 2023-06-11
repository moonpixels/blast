<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class CurrentUserResource extends JsonResource
{
    /**
     * Disable wrapping the outermost layer of the resource.
     */
    public static $wrap = null;

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
            'initials' => $this->initials,
            'two_factor_enabled' => $this->two_factor_enabled,

            'teams' => $this->allTeams()->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                ];
            }),
        ];
    }
}
