<?php

namespace Domain\Link\Actions;

use Domain\Link\Models\Link;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteLinkAction
{
    use AsAction;

    /**
     * Delete a link.
     */
    public function handle(Link $link): bool
    {
        return $link->delete();
    }
}
