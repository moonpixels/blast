<?php

namespace App\Domain\Team\Events;

use App\Domain\Team\Models\Team;
use Illuminate\Foundation\Events\Dispatchable;

class TeamDeleted
{
    use Dispatchable;

    public function __construct(public readonly Team $team)
    {
    }
}
