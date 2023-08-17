<?php

namespace App\Domain\Link\Actions;

use App\Domain\Link\Models\Link;
use App\Support\Concerns\HasUrlInput;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteLink
{
    use AsAction, HasUrlInput;

    /**
     * Delete the given link.
     */
    public function handle(Link $link): bool
    {
        return $link->delete();
    }
}
