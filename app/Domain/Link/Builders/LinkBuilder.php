<?php

namespace Domain\Link\Builders;

use Domain\Link\Models\Link;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Link
 */
class LinkBuilder extends Builder
{
    /**
     * Filter links available to a user.
     */
    public function forUser(User $user): static
    {
        $this->whereIn('team_id', $user->allTeams()->pluck('id'));

        return $this;
    }
}
