<?php

namespace Domain\Team\Builders;

use Domain\Team\Models\Team;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Team
 */
class TeamBuilder extends Builder
{
    /**
     * Filter teams by personal teams.
     */
    public function personal(): static
    {
        return $this->where('personal_team', true);
    }

    /**
     * Filter teams by not personal teams.
     */
    public function notPersonal(): static
    {
        return $this->where('personal_team', false);
    }
}
