<?php

namespace Domain\Team\Events;

use Domain\Team\Models\Team;
use Illuminate\Foundation\Events\Dispatchable;

class TeamDeletedEvent
{
    use Dispatchable;

    public function __construct(
        public readonly Team $team
    ) {
    }
}
